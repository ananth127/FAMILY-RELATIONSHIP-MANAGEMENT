<?php
$host = 'localhost';
$db = 'relationship';
$user = 'root';
$password = '';

// Create PDO instance
$dsn = "mysql:host=$host;dbname=$db;charset=UTF8";
try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$relationshipMap = [
    // Self
    "Self" => "Self",
    
    // Parents
    "Father of Self" => "Father",
    "Mother of Self" => "Mother",
    
    // Grandparents
    "Father of Father of Self" => "Paternal Grandfather",
    "Mother of Father of Self" => "Paternal Grandmother",
    "Father of Mother of Self" => "Maternal Grandfather",
    "Mother of Mother of Self" => "Maternal Grandmother",
    
    // Siblings
    "Brother of Self" => "Brother",
    "Sister of Self" => "Sister",
    
    // Children
    "Son of Self" => "Son",
    "Daughter of Self" => "Daughter",
    
    // Spouses
    "Husband of Self" => "Husband",
    "Wife of Self" => "Wife",
];

function fetchFamilyTree($pdo, $id) {
    global $relationshipMap;
    
    // Initialize the queue for BFS
    $queue = [];
    $visited = [];
    $treeData = [];
    array_push($queue, ["id" => $id, "relationship" => "Self", "depth" => 0, "parent" => null]);
    $visited[$id] = true;
    
    while (!empty($queue)) {
        $current = array_shift($queue);
        $currentId = $current['id'];
        $currentRelationship = $current['relationship'];
        $currentDepth = $current['depth'];
        $parent = $current['parent'];
        
        // Fetch the current person
        $stmt = $pdo->prepare("SELECT id, name, gender, father_id, mother_id, marital_id FROM userss WHERE id = ?");
        $stmt->execute([$currentId]);
        $person = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($person) {
            $name = htmlspecialchars($person['name']);
            $relationshipName = $relationshipMap[$currentRelationship] ?? $currentRelationship;
            $treeData[] = ["id" => $person['id'], "name" => $name, "relationship" => $relationshipName, "parent" => $parent];
            
            if ($currentDepth < 6) {
                // Enqueue parents
                if ($person['father_id'] && !isset($visited[$person['father_id']])) {
                    array_push($queue, ["id" => $person['father_id'], "relationship" => "Father of $currentRelationship", "depth" => $currentDepth + 1, "parent" => $person['id']]);
                    $visited[$person['father_id']] = true;
                }
                if ($person['mother_id'] && !isset($visited[$person['mother_id']])) {
                    array_push($queue, ["id" => $person['mother_id'], "relationship" => "Mother of $currentRelationship", "depth" => $currentDepth + 1, "parent" => $person['id']]);
                    $visited[$person['mother_id']] = true;
                }
                
                // Enqueue descendants
                $descendantStmt = $pdo->prepare("SELECT id, gender FROM userss WHERE father_id = ? OR mother_id = ?");
                $descendantStmt->execute([$currentId, $currentId]);
                while ($descendant = $descendantStmt->fetch(PDO::FETCH_ASSOC)) {
                    if (!isset($visited[$descendant['id']])) {
                        $childRelationship = ($descendant['gender'] === 'male') ? "Son" : "Daughter";
                        array_push($queue, ["id" => $descendant['id'], "relationship" => "$childRelationship of $currentRelationship", "depth" => $currentDepth + 1, "parent" => $person['id']]);
                        $visited[$descendant['id']] = true;
                    }
                }
                
                // Enqueue spouse
                if ($person['marital_id'] && !isset($visited[$person['marital_id']])) {
                    $spouseStmt = $pdo->prepare("SELECT id, gender FROM userss WHERE id = ?");
                    $spouseStmt->execute([$person['marital_id']]);
                    $spouse = $spouseStmt->fetch(PDO::FETCH_ASSOC);
                    if ($spouse) {
                        $spouseRelationship = ($spouse['gender'] === 'male') ? "Husband" : "Wife";
                        array_push($queue, ["id" => $spouse['id'], "relationship" => "$spouseRelationship of $currentRelationship", "depth" => $currentDepth + 1, "parent" => $person['id']]);
                        $visited[$spouse['id']] = true;
                    }
                }
            }
        }
    }
    return json_encode($treeData);
}

$id = $_POST['personId'] ?? 1; // Default to ID 1 if not provided
$treeData = fetchFamilyTree($pdo, $id);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Family Tree</title>
    <style>
        .node circle {
            fill: #fff;
            stroke: steelblue;
            stroke-width: 3px;
        }

        .node text {
            font: 12px sans-serif;
        }

        .link {
            fill: none;
            stroke: #ccc;
            stroke-width: 2px;
        }

        .tooltip {
            position: absolute;
            text-align: center;
            width: 120px;
            height: auto;
            padding: 5px;
            font: 12px sans-serif;
            background: lightsteelblue;
            border: 0px;
            border-radius: 8px;
            pointer-events: none;
        }
    </style>
    <script src="https://d3js.org/d3.v6.min.js"></script>
</head>
<body>
    <h1>Family Tree</h1>
    <div id="tree"></div>
    <div id="tooltip" class="tooltip" style="opacity: 0;"></div>

    <script>
        var treeData = <?php echo $treeData; ?>;

        // Convert flat data to hierarchical data
        function convertToHierarchy(data) {
            let map = new Map(data.map(d => [d.id, { ...d, children: [] }]));
            let roots = [];
            for (let node of map.values()) {
                if (node.parent === null) {
                    roots.push(node);
                } else {
                    map.get(node.parent).children.push(node);
                }
            }
            return roots.length === 1 ? roots[0] : { id: "root", children: roots };
        }

        var root = convertToHierarchy(treeData);

        // Set the dimensions and margins of the diagram
        var margin = { top: 20, right: 90, bottom: 30, left: 90 },
            width = 960 - margin.left - margin.right,
            height = 500 - margin.top - margin.bottom;

        // Append the svg object to the body of the page
        var svg = d3.select("#tree").append("svg")
            .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
            .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

        var i = 0,
            duration = 750,
            root;

        // Declare a tree layout and assign the size
        var treemap = d3.tree().size([height, width]);

        // Assigns parent, children, height, depth
        root = d3.hierarchy(root, function (d) { return d.children; });
        root.x0 = height / 2;
        root.y0 = 0;

        // Collapse after the second level
        function collapse(d) {
            if (d.children) {
                d._children = d.children
                d._children.forEach(collapse)
                d.children = null
            }
        }

        root.children.forEach(collapse);
        update(root);

        function update(source) {

            // Assigns the x and y position for the nodes
            var treeData = treemap(root);

            // Compute the new tree layout.
            var nodes = treeData.descendants(),
                links = treeData.descendants().slice(1);

            // Normalize for fixed-depth.
            nodes.forEach(function (d) { d.y = d.depth * 180 });

            // ****************** Nodes section ***************************

            // Update the nodes...
            var node = svg.selectAll('g.node')
                .data(nodes, function (d) { return d.id || (d.id = ++i); });

            // Enter any new modes at the parent's previous position.
            var nodeEnter = node.enter().append('g')
                .attr('class', 'node')
                .attr("transform", function (d) {
                    return "translate(" + source.y0 + "," + source.x0 + ")";
                })
                .on('click', click)
                .on('mouseover', mouseover)
                .on('mouseout', mouseout)
                .call(d3.drag()
                    .on("start", dragstart)
                    .on("drag", dragged)
                    .on("end", dragend));

            // Add Circle for the nodes
            nodeEnter.append('circle')
                .attr('class', 'node')
                .attr('r', 1e-6)
                .style("fill", function (d) {
                    return d._children ? "lightsteelblue" : "#fff";
                });

            // Add labels for the nodes
            nodeEnter.append('text')
                .attr("dy", ".35em")
                .attr("x", function (d) {
                    return d.children || d._children ? -13 : 13;
                })
                .attr("text-anchor", function (d) {
                    return d.children || d._children ? "end" : "start";
                })
                .text(function (d) { return d.data.name + " (" + d.data.relationship + ")"; });

            // UPDATE
            var nodeUpdate = nodeEnter.merge(node);

            // Transition to the proper position for the node
            nodeUpdate.transition()
                .duration(duration)
                .attr("transform", function (d) {
                    return "translate(" + d.y + "," + d.x + ")";
                });

            // Update the node attributes and style
            nodeUpdate.select('circle.node')
                .attr('r', 10)
                .style("fill", function (d) {
                    return d._children ? "lightsteelblue" : "#fff";
                })
                .attr('cursor', 'pointer');


            // Remove any exiting nodes
            var nodeExit = node.exit().transition()
                .duration(duration)
                .attr("transform", function (d) {
                    return "translate(" + source.y + "," + source.x + ")";
                })
                .remove();

            // On exit reduce the node circles size to 0
            nodeExit.select('circle')
                .attr('r', 1e-8);

            // On exit reduce the opacity of text labels
            nodeExit.select('text')
                .style('fill-opacity', 1e-8);

            // ****************** links section ***************************

            // Update the links...
            var link = svg.selectAll('path.link')
                .data(links, function (d) { return d.id; });

            // Enter any new links at the parent's previous position.
            var linkEnter = link.enter().insert('path', "g")
                .attr("class", "link")
                .attr('d', function (d) {
                    var o = { x: source.x0, y: source.y0 }
                    return diagonal(o, o)
                });

            // UPDATE
            var linkUpdate = linkEnter.merge(link);

            // Transition back to the parent element position
            linkUpdate.transition()
                .duration(duration)
                .attr('d', function (d) { return diagonal(d, d.parent) });

            // Remove any exiting links
            var linkExit = link.exit().transition()
                .duration(duration)
                .attr('d', function (d) {
                    var o = { x: source.x, y: source.y }
                    return diagonal(o, o)
                })
                .remove();

            // Store the old positions for transition.
            nodes.forEach(function (d) {
                d.x0 = d.x;
                d.y0 = d.y;
            });

            // Creates a curved (diagonal) path from parent to the child nodes
            function diagonal(s, d) {

                path = `M ${s.y} ${s.x}
                        C ${(s.y + d.y) / 2} ${s.x},
                          ${(s.y + d.y) / 2} ${d.x},
                          ${d.y} ${d.x}`

                return path
            }

            // Toggle children on click.
            function click(event, d) {
                if (d.children) {
                    d._children = d.children;
                    d.children = null;
                } else {
                    d.children = d._children;
                    d._children = null;
                }
                update(d);
            }

            // Show tooltip on mouseover
            function mouseover(event, d) {
                d3.select("#tooltip")
                    .style("left", (event.pageX + 10) + "px")
                    .style("top", (event.pageY - 10) + "px")
                    .transition()
                    .duration(200)
                    .style("opacity", .9)
                    .text(d.data.name + " (" + d.data.relationship + ")");
            }

            // Hide tooltip on mouseout
            function mouseout(event, d) {
                d3.select("#tooltip")
                    .transition()
                    .duration(500)
                    .style("opacity", 0);
            }

            // Drag functions
            function dragstart(event, d) {
                d3.select(this).raise().attr("stroke", "black");
            }

            function dragged(event, d) {
                d.x0 = d.x = event.x;
                d.y0 = d.y = event.y;
                d3.select(this).attr("transform", `translate(${d.y},${d.x})`);
                update(d);
            }

            function dragend(event, d) {
                d3.select(this).attr("stroke", null);
            }
        }
    </script>
</body>
</html>
