<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Family Tree</title>
    <style>
        .node circle {
            fill: #fff;
            stroke: steelblue;
            stroke-width: 2px;
        }

        .node text {
            font: 12px sans-serif;
        }

        .link {
            fill: none;
            stroke: #ccc;
            stroke-width: 2px;
        }
    </style>
</head>

<body>
    <svg id="treeSvg" width="960" height="600"></svg>

    <script src="https://d3js.org/d3.v6.min.js"></script>
    <script>
        // Function to render the tree with given data
        function renderTree(treeData) {
            // Set up the tree layout
            var treeLayout = d3.tree().size([600, 400]);

            // Create a root node
            var rootNode = d3.hierarchy(treeData);

            // Assigns the x and y position for the nodes
            treeLayout(rootNode);

            // Remove any existing SVG elements
            d3.select("#treeSvg").selectAll("*").remove();

            // Append the SVG element
            var svg = d3.select("#treeSvg")
                .attr("width", 960)
                .attr("height", 600)
                .append("g")
                .attr("transform", "translate(40,20)");

            // Define the links
            var links = svg.selectAll(".link")
                .data(rootNode.links())
                .enter()
                .append("path")
                .attr("class", "link")
                .attr("d", d3.linkVertical()
                    .x(function (d) { return d.x; })
                    .y(function (d) { return d.y; }));

            // Define the nodes
            var nodes = svg.selectAll(".node")
                .data(rootNode.descendants())
                .enter()
                .append("g")
                .attr("class", "node")
                .attr("transform", function (d) {
                    return "translate(" + d.x + "," + d.y + ")";
                });

            // Append circles to the nodes
            nodes.append("circle")
                .attr("r", 5)
                .style("fill", "#fff")
                .style("stroke", "steelblue")
                .style("stroke-width", "2px");

            // Append text to the nodes
            nodes.append("text")
                .attr("dy", ".35em")
                .attr("x", function (d) { return d.children ? -13 : 13; })
                .style("text-anchor", function (d) {
                    return d.children ? "end" : "start";
                })
                .text(function (d) { return d.data.name; });
        }

        // Define initial tree data
        var initialTreeData = {
            "name": "MAJA",
            "children": [
                {
                    "name": "RAJA",
                    "children": [
                        { "name": "RAM" },
                        { "name": "RAMI" }
                    ]
                },
                { "name": "SAJA" },
                { "name": "SONY" },
                { "name": "SONI" }
            ]
        };

        // Render the initial tree
        renderTree(initialTreeData);

        // Example of updating tree data and re-rendering
        setTimeout(function () {
            var updatedTreeData = {
                "name": "Updated Root",
                "children": [
                    {
                        "name": "New Child",
                        "children": [
                            { "name": "New Grandchild" },
                            { "name": "Another New Grandchild" }
                        ]
                    },
                    { "name": "Another New Child" },
                    { "name": "Yet Another New Child" }
                ]
            };

            // Update the tree with new data
            renderTree(updatedTreeData);
        }, 300000); // Update after 3 seconds for demonstration
    </script>





    <style>
        .node circle {
            fill: #fff;
            stroke: steelblue;
            stroke-width: 2px;
        }

        .node text {
            font: 12px sans-serif;
        }

        .link {
            fill: none;
            stroke: #ccc;
            stroke-width: 2px;
        }
    </style>

    <svg id="treeSvg" width="960" height="600"></svg>

    <script src="https://d3js.org/d3.v6.min.js"></script>
    <script>
        // Function to render the tree with given data
        function renderTree(treeData) {
            // Set up the tree layout
            var treeLayout = d3.tree().size([600, 400]);

            // Create a root node
            var rootNode = d3.hierarchy(treeData);

            // Assigns the x and y position for the nodes
            treeLayout(rootNode);

            // Remove any existing SVG elements
            d3.select("#treeSvg").selectAll("*").remove();

            // Append the SVG element
            var svg = d3.select("#treeSvg")
                .attr("width", 960)
                .attr("height", 600)
                .append("g")
                .attr("transform", "translate(40,20)");

            // Define the links
            var links = svg.selectAll(".link")
                .data(rootNode.links())
                .enter()
                .append("path")
                .attr("class", "link")
                .attr("d", d3.linkVertical()
                    .x(function (d) { return d.x; })
                    .y(function (d) { return d.y; }));

            // Define the nodes
            var nodes = svg.selectAll(".node")
                .data(rootNode.descendants())
                .enter()
                .append("g")
                .attr("class", "node")
                .attr("transform", function (d) {
                    return "translate(" + d.x + "," + d.y + ")";
                });

            // Append circles to the nodes
            nodes.append("circle")
                .attr("r", 5)
                .style("fill", "#fff")
                .style("stroke", "steelblue")
                .style("stroke-width", "2px");

            // Append text to the nodes
            nodes.append("text")
                .attr("dy", ".35em")
                .attr("x", function (d) { return d.children ? -13 : 13; })
                .style("text-anchor", function (d) {
                    return d.children ? "end" : "start";
                })
                .text(function (d) { return d.data.name; });
        }

    </script>