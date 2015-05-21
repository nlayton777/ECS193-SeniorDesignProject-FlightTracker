package sample;

import javafx.application.Application;
import javafx.fxml.FXMLLoader;
import javafx.scene.Parent;
import javafx.scene.Scene;
import javafx.stage.Stage;
import java.util.regex.Pattern;
import java.util.regex.Matcher;

public class Main{

    public static void main(String[] args) {
        FlightData fData = new FlightData();


        //Take in the Orgin(args[0]) and Destination(args[1]) Airport Codes)
        fData.setFrom(args[0]);
        fData.setTo(args[1]);
            
        //Start the data extractor
        boolean pass = fData.dataExtractor();
        
        //Print an error statement if hte extractor fails
        if (!pass)                                                      
        {
            System.out.println("\nERROR: " + fData.getErrorMessage());
        }

    }
}
