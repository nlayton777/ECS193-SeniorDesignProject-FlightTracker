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

        fData.setFrom(args[0]);
        fData.setTo(args[1]);

        boolean pass = fData.dataExtractor();
        //boolean pass = true;


        //lets print stuff out
        if (!pass)                                                      //failure
        {
            System.out.println("\nERROR: " + fData.getErrorMessage());
        }

    }
}
