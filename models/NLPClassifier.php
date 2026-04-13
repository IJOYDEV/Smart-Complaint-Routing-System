<?php

class NLPClassifier {

    public function classifyComplaint($text) {

        $text = strtolower($text);

        if (strpos($text, "road")    !== false || 
            strpos($text, "pothole") !== false ||
            strpos($text, "bridge")  !== false ||
            strpos($text, "tarmac")  !== false) {
            return ["Infrastructure", 3];
        }

        if (strpos($text, "water")    !== false || 
            strpos($text, "pipe")     !== false ||
            strpos($text, "shortage") !== false ||
            strpos($text, "tap")      !== false) {
            return ["Water Issue", 2];
        }

        if (strpos($text, "garbage") !== false || 
            strpos($text, "waste")   !== false ||
            strpos($text, "litter")  !== false ||
            strpos($text, "dump")    !== false) {
            return ["Sanitation", 6];
        }

        if (strpos($text, "health")   !== false || 
            strpos($text, "disease")  !== false ||
            strpos($text, "hospital") !== false ||
            strpos($text, "clinic")   !== false) {
            return ["Health Issue", 5];
        }

        if (strpos($text, "electricity") !== false || 
            strpos($text, "power")        !== false ||
            strpos($text, "blackout")     !== false ||
            strpos($text, "light")        !== false) {
            return ["Electricity Issue", 4];
        }

        if (strpos($text, "internet") !== false || 
            strpos($text, "system")   !== false ||
            strpos($text, "network")  !== false ||
            strpos($text, "computer") !== false) {
            return ["IT Issue", 1];
        }

        return ["General", 1];
    }
}
?>