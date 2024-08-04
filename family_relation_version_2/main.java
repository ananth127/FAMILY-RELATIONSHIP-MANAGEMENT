import java.util.*;

public class Main{
    public static void main(String[] args){
        Scanner sc = new Scanner(System.in);
        String str=sc.nextLine();
        char ch[]=str.toCharArray();
        int len=ch.length;
        int i=0;
        int j=ch.length-1;
            while(i<len){
                if(i>=j){
                    System.out.println("crosses i= "+i+" j="+j);
                    break;
                    // if crosses the limit 
                }
                else if(Character.isLetter(ch[i])&&Character.isLetter(ch[j])){
                        char temp=ch[i];
                        ch[i]=ch[j];
                        ch[j]=temp;
                        System.out.println("Swaping = "+ch[i]+" "+ch[j]);
                        i++;
                        j--;
                         }
                    
                    else if(Character.isLetter(ch[i])){
                        System.out.println("decrement j = "+ch[i]+" "+ch[j]);
                        j--;
                    }
                   else if(Character.isLetter(ch[j])){
                        System.out.println("increment i = "+ch[i]+" "+ch[j]);
                        i++;
                    }
                    else{
                         System.out.println("increment decrement i&j special char both = "+ch[i]+" "+ch[j]);
                        i++;
                        j--;
                    }
                    }
                 System.out.print(new String(ch));
        }
    }