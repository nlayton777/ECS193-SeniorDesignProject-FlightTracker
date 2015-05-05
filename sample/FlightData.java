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

    //initializations when instantiating the object
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

        //build the URL and prepare to read the page
        String hopperURL = "http://www.hopper.com/flights/from-" + getFrom() + "/to-" + getTo() + "/guide";
        URL webPage;
        try
        {
            webPage = new URL(hopperURL);
            BufferedReader in = new BufferedReader(new InputStreamReader(webPage.openStream()));

            //read one line at a time
            String inputLine;
            while (((inputLine = in.readLine()) != null))
            {
                //find the section of the page that contains the table
                if (inputLine.contains("<div class='error-page'>")){
                    errorMessage = "Information does not Exist";
                    return false;
                }
                if (inputLine.contains("<h2 id='deals' style='margin-top:1em'>"))
                {
                    while (!inputLine.contains("</h2>"))
                    {
                        inputLine = in.readLine();

                        //the messages are either inside a h1 or h2 header block
                        if (inputLine.contains("<em>"))
                        {
                            goodPrice = inputLine.replace("<em>","").replace(".</em>", "");
                            rawTableCapture[2] = goodPrice;
                        }
                    }
                }
                else if(inputLine.contains("<h2 id='dow'>"))
                {
                    while (!inputLine.contains("</h2>"))
                    {
                        inputLine = in.readLine();

                        if(inputLine.contains("Fly out on a"))
                        {
                            Pattern pattern = Pattern.compile("<em>([^<]*)</em>");

                            Matcher matcher = pattern.matcher(inputLine);

                            int count = 0;

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
                else if(inputLine.contains("<h2 id='airlines'>")){
                    while(!inputLine.contains("</h2>")){
                        inputLine = in.readLine();

                        Pattern pattern = Pattern.compile("<em>([^<]*)</em>");
                        Matcher matcher = pattern.matcher(inputLine);

                        while(matcher.find() && altAirlineCount < 3) {
                            alternateAirline[altAirlineCount] = matcher.group(1);
                            altAirlineCount++;
                        }
                    }
                }
                else if(inputLine.contains("<div class='airport-header'>ALTERNATE DEPARTURE AIRPORTS</div>")){

                    while(!inputLine.contains("</a></span>")) {
                        inputLine = in.readLine();
                        if(inputLine.contains("<a href=")){
                            Pattern pattern = Pattern.compile("<span itemprop=\"name\">([^<]*)</span>");
                            Matcher matcher = pattern.matcher(inputLine);
                            matcher.find();
                            alternateFromAirport = matcher.group(1);
                        }
                    }
                }
                else if(inputLine.contains("<div class='airport-header'>ALTERNATE ARRIVAL AIRPORTS</div>")){

                    while(!inputLine.contains("</a></span>")) {
                        inputLine = in.readLine();
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
