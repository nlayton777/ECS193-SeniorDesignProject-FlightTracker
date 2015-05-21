package sample;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.lang.reflect.Array;
import java.net.URL;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

public class FlightData {

    //Initializations
    private static String to = "";
    private static String from = "";
    private static String goodPrice = "";
    private static String flyOutOn="";
    private static String flyBackOn="";
    private static String[] alternateAirline= new String[3];
    private static int altAirlineCount = 0;
    private static String alternateFromAirport = "";
    private static String alternateToAirport = "";
    private static String errorMessage = "";
    private static String[] rawTableCapture = new String[5];

    public static boolean dataExtractor()
    {

        rawTableCapture[0] = getTo();
        rawTableCapture[1] = getFrom();

        //build the URL
        String hopperURL = "http://www.hopper.com/flights/from-" + getFrom() + "/to-" + getTo() + "/guide";
        URL webPage;
        try
        {
            webPage = new URL(hopperURL);
            BufferedReader in = new BufferedReader(new InputStreamReader(webPage.openStream()));
            
            //NOTE: 
                //To understand how the sections are ogranized, look at the source fod for any report
                //They are all organized in a similar manner
                //However, some fields may be missing if Hopper doesn't have enough data on that flight

            //Read one line at a time to find lines that contain pertinent information
            String inputLine;
            while (((inputLine = in.readLine()) != null))
            {
                //See if the Page is an Error Page(the information does not exist)
                if (inputLine.contains("<div class='error-page'>")){
                    errorMessage = "Information does not Exist";
                    return false;
                }
                
                //Find the section that has the price for a good deal
                if (inputLine.contains("<h2 id='deals' style='margin-top:1em'>"))
                {
                    while (!inputLine.contains("</h2>"))
                    {
                        inputLine = in.readLine();
					
                        //Parse the input line to get the singular good price value
                        if (inputLine.contains("<em>"))
                        {
                            goodPrice = inputLine.replace("<em>","").replace(".</em>", "");
                            rawTableCapture[2] = goodPrice;
                        }
                    }
                }
                
                //Find the section that has the best days to fly out and back on
                else if(inputLine.contains("<h2 id='dow'>"))
                {
                    while (!inputLine.contains("</h2>"))
                    {
                        inputLine = in.readLine();

                        //Parse the input line to get two values (when to fly out and back)
                        if(inputLine.contains("Fly out on a"))
                        {
                            Pattern pattern = Pattern.compile("<em>([^<]*)</em>");

                            Matcher matcher = pattern.matcher(inputLine);

                            int count = 0;
                            
                            //The above pattern matches two values:
                            //When to fly out and when to fly back
                            while (matcher.find()) {
                                switch (count) {
                                    case 0:
                                        flyOutOn = matcher.group(1);
                                        count++;
                                        break;
                                    case 1:
                                        flyBackOn = matcher.group(1);
                                        count = 0;
                                        break;
                                }

                            }

                        }
                    }
                }
                
                //Find the section that has the cheapest airlines for this flight
                else if(inputLine.contains("<h2 id='airlines'>")){
                    while(!inputLine.contains("</h2>")){
                        inputLine = in.readLine();

                        Pattern pattern = Pattern.compile("<em>([^<]*)</em>");
                        Matcher matcher = pattern.matcher(inputLine);

                        //Parse the input line for alternate airlines
                        //Only stores a maximum of 3 alternate airlines
                        while(matcher.find() && altAirlineCount < 3) {
                            alternateAirline[altAirlineCount] = matcher.group(1);
                            altAirlineCount++;
                        }
                    }
                }
                
                //Find the section for alternate origin airports
                else if(inputLine.contains("<div class='airport-header'>ALTERNATE DEPARTURE AIRPORTS</div>")){

                    while(!inputLine.contains("</a></span>")) {
                        inputLine = in.readLine();
                        
                        //Parse the input for an alternate origin airport
                        //Only stores at most 1 alternate origin airport
                        if(inputLine.contains("<a href=")){
                            Pattern pattern = Pattern.compile("<span itemprop=\"name\">([^<]*)</span>");
                            Matcher matcher = pattern.matcher(inputLine);
                            matcher.find();
                            alternateFromAirport = matcher.group(1);
                        }
                    }
                }
                
                //Find the section for alternate destination airports
                else if(inputLine.contains("<div class='airport-header'>ALTERNATE ARRIVAL AIRPORTS</div>")){

                    while(!inputLine.contains("</a></span>")) {
                        inputLine = in.readLine();
                        
                        //Parse the input for an alternate destination airport
                        //Only stores at most 1 alternate destination airport
                        if(inputLine.contains("<a href=")){
                            Pattern pattern = Pattern.compile("<span itemprop=\"name\">([^<]*)</span>");
                            Matcher matcher = pattern.matcher(inputLine);
                            matcher.find();
                            alternateToAirport = matcher.group(1);
                        }
                    }
                }
            }

            in.close();
            
            //Output a comma separated string with the values we want
            System.out.print(from + ", " + to + ", " + goodPrice + ", " + flyBackOn + ", " + flyOutOn + ", " );
            System.out.print(altAirlineCount + ", ");
            for (int i = 0; i < altAirlineCount; i++){
                System.out.print(alternateAirline[i] + ", ");
            }
            System.out.print(alternateFromAirport + ", ");
            System.out.print(alternateToAirport + "\n");

            return true;
        }
        catch (java.io.IOException IOException )
        {	
            //Return an error if couldn't connect to page
            errorMessage = "Internet connection failed";
            return false;
        }
    }

    public static void setTo(String to) {FlightData.to = to;}

    public static String getTo() {return FlightData.to;}

    public static void setFrom(String from) {FlightData.from = from;}

    public static String getFrom() {return FlightData.from;}

    public static String getErrorMessage() {return FlightData.errorMessage;}


}
