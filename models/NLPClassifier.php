<?php

class NLPClassifier {

    private $departments = [
        1 => "IT Services",
        2 => "Water Services",
        3 => "Roads and Transport",
        4 => "Electricity",
        5 => "Public Health",
        6 => "Sanitation"
    ];

    private $keywords = [
        3 => [ // Roads and Transport
            'road' => 3, 'pothole' => 3, 'bridge' => 3, 'tarmac' => 3,
            'highway' => 3, 'street' => 2, 'traffic' => 3, 'junction' => 2,
            'pavement' => 2, 'culvert' => 3, 'bypass' => 2, 'footbridge' => 3,
            'matatu' => 2, 'transport' => 2, 'boda' => 2, 'motorist' => 2,
            'sinkhole' => 2, 'gravel' => 2, 'tarmacking' => 3, 'roadblock' => 2,
            'roundabout' => 2, 'signpost' => 2, 'guardrail' => 2, 'drainage' => 1
        ],
        2 => [ // Water Services
            'water' => 3, 'pipe' => 2, 'tap' => 3, 'borehole' => 3,
            'meter' => 2, 'supply' => 1, 'leak' => 2, 'pressure' => 2,
            'rationing' => 3, 'hydrant' => 3, 'contaminated' => 2, 'kiosk' => 2,
            'plumbing' => 2, 'waterborne' => 2, 'reservoir' => 3, 'tank' => 2,
            'pump' => 2, 'salty' => 2, 'murky' => 2, 'rusty' => 2
        ],
        4 => [ // Electricity
            'electricity' => 3, 'power' => 3, 'blackout' => 3, 'outage' => 3,
            'transformer' => 3, 'voltage' => 3, 'wire' => 2, 'electric' => 3,
            'prepaid' => 3, 'token' => 2, 'meter' => 2, 'pole' => 2,
            'surge' => 3, 'generator' => 2, 'substation' => 3, 'phase' => 2,
            'insulator' => 3, 'load shedding' => 3, 'streetlight' => 2, 'sparks' => 2
        ],
        5 => [ // Public Health
            'health' => 3, 'disease' => 3, 'hospital' => 3, 'clinic' => 3,
            'malaria' => 3, 'cholera' => 3, 'typhoid' => 3, 'vaccination' => 3,
            'medicine' => 3, 'doctor' => 3, 'mosquito' => 3, 'epidemic' => 3,
            'infection' => 3, 'hygiene' => 2, 'sanitation' => 1, 'outbreak' => 3,
            'pest' => 2, 'rats' => 2, 'food safety' => 3, 'contamination' => 2,
            'nurse' => 2, 'pharmacy' => 2, 'ambulance' => 3, 'rabies' => 3
        ],
        6 => [ // Sanitation
            'garbage' => 3, 'waste' => 3, 'litter' => 3, 'dump' => 3,
            'sewage' => 3, 'sewer' => 3, 'toilet' => 3, 'drain' => 2,
            'manhole' => 3, 'septic' => 3, 'refuse' => 3, 'collection' => 2,
            'smell' => 2, 'odor' => 2, 'bin' => 2, 'skip' => 2,
            'latrine' => 3, 'flush' => 2, 'grease trap' => 3, 'cesspit' => 3
        ],
        1 => [ // IT Services
            'internet' => 3, 'system' => 2, 'network' => 3, 'computer' => 3,
            'portal' => 3, 'website' => 3, 'online' => 3, 'server' => 3,
            'software' => 3, 'application' => 2, 'login' => 3, 'password' => 3,
            'wifi' => 3, 'digital' => 2, 'database' => 3, 'email' => 2,
            'printer' => 2, 'cctv' => 3, 'error' => 2, 'crashed' => 2,
            'offline' => 3, 'payment system' => 3, 'e-citizen' => 3, 'helpdesk' => 3
        ]
    ];

    public function classifyComplaint($text) {
        $text   = strtolower(trim($text));
        $scores = [];

        foreach ($this->keywords as $dept_id => $words) {
            $score = 0;
            foreach ($words as $keyword => $weight) {
                // Count how many times keyword appears
                $count  = substr_count($text, $keyword);
                $score += $count * $weight;
            }
            $scores[$dept_id] = $score;
        }

        // Get the department with highest score
        arsort($scores);
        $top_dept_id = array_key_first($scores);
        $top_score   = $scores[$top_dept_id];

        // If no keywords matched at all, return General/IT
        if ($top_score === 0) {
            return ["General", 1];
        }

        return [$this->departments[$top_dept_id], $top_dept_id];
    }

    // Returns all scores - useful for debugging
    public function getScores($text) {
        $text   = strtolower(trim($text));
        $scores = [];

        foreach ($this->keywords as $dept_id => $words) {
            $score = 0;
            foreach ($words as $keyword => $weight) {
                $count  = substr_count($text, $keyword);
                $score += $count * $weight;
            }
            $scores[$this->departments[$dept_id]] = $score;
        }

        arsort($scores);
        return $scores;
    }
}
?>