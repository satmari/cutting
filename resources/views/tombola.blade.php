@extends('app')

@section('content')
<!DOCTYPE html>
<html>
<head>
    <title>Tombola Random Number Generator</title>
    <style>
        .container {
            width: 300px;
            margin: 0 auto;
            text-align: center;
        }

        .number {
            font-size: 48px;
            margin-bottom: 20px;
        }

        .name {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .stop-button {
            padding: 10px 20px;
            font-size: 18px;
            background-color: #ff0000;
            color: #ffffff;
            border: none;
            cursor: pointer;
        }
	
    </style>
	<meta charset="UTF-8">
</head>
<body>
    <div class="container">
        <h1>Tombola Random Number Generator</h1>
        <div class="number" id="randomNumber">R0____</div>
        <div class="name" id="randomName"></div>
        
		
		<button id="startButton">Start</button>
<button id="stopButton">Stop</button>
<button id="saveButton">Save</button>
<button id="clearButton">Clear</button>
<ul id="lastSelectedNumbers"></ul>

    </div>
	<script>
const sqlite3 = require('sqlite3').verbose();

// Connect to the database
const db = new sqlite3.Database('results.db');

// Create a table if it doesn't exist
db.run(`
  CREATE TABLE IF NOT EXISTS results (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    number TEXT,
    name TEXT
  )
`);

module.exports = db;
	</script>
    <script>
	
	
	
	
	
	var possibleNumbers = [
 { number: "R00003", name: "Marijana Mašić" },
{ number: "R00005", name: "Tamara Malević" },
{ number: "R00007", name: "Dijana Šegina" },
{ number: "R00009", name: "Marijana Zarić" },
{ number: "R00011", name: "Sonja Turu" },
{ number: "R00014", name: "TAMARA VOJNIĆ HAJDUK" },
{ number: "R00022", name: "Atila Satmari" },
{ number: "R00024", name: "Sandra PEJIĆ SADŽAK" },
{ number: "R00029", name: "Ivana Daraboš" },
{ number: "R00031", name: "Boris Mihaljević" },
{ number: "R00032", name: "Ivona Živković" },
{ number: "R00033", name: "Mira Stipić" },
{ number: "R00037", name: "Dušica Bosić" },
{ number: "R00038", name: "Aslan BAJRAMI" },
{ number: "R00041", name: "Maja Bašić Palković" },
{ number: "R00046", name: "Snežana Bastaja" },
{ number: "R00051", name: "SANDRA PETROVIĆ" },
{ number: "R00064", name: "Silvester Venič" },
{ number: "R00071", name: "JASMINA SIMIN" },
{ number: "R00076", name: "Maja Puhalak" },
{ number: "R00079", name: "ANDREA IKIĆ" },
{ number: "R00082", name: "Snežana Kujundžić" },
{ number: "R00083", name: "Tamara Racić" },
{ number: "R00091", name: "Nataša Salma" },
{ number: "R00098", name: "SUZANA KONAKOV" },
{ number: "R00099", name: "SANJA STOJČOV" },
{ number: "R00100", name: "Monika Čisar" },
{ number: "R00101", name: "SANJA IŠTVANČIĆ" },
{ number: "R00104", name: "Snežana Moravčić" },
{ number: "R00106", name: "Gordana Deak" },
{ number: "R00107", name: "Sanja Mujović" },
{ number: "R00108", name: "Emilija Jaramazović" },
{ number: "R00110", name: "SANJA ĐUKIĆ" },
{ number: "R00112", name: "Ivana Dulić M." },
{ number: "R00119", name: "NATAŠA MICIĆ" },
{ number: "R00121", name: "Zlatko Vojnić Hajduk" },
{ number: "R00133", name: "Sanela Švraka" },
{ number: "R00138", name: "IVANA LAJSNER" },
{ number: "R00140", name: "Dijana Šarčević" },
{ number: "R00147", name: "Jasmina Radoman" },
{ number: "R00149", name: "Kristina Turu" },
{ number: "R00153", name: "Nevenka Banković" },
{ number: "R00156", name: "Slađana Celva" },
{ number: "R00160", name: "Sanja Cvijanov" },
{ number: "R00163", name: "Sanela Vukelić" },
{ number: "R00165", name: "SILVANA ŠARČEVIĆ" },
{ number: "R00171", name: "ANITA HEGEDUS BARAT" },
{ number: "R00175", name: "Hajnalka Kolar" },
{ number: "R00178", name: "Daniela Mihaljević" },
{ number: "R00181", name: "Zorica Gabrić" },

];

	var possibleNumbers = [
 { number: "R00003", name: "Marijana Mašić" },
{ number: "R00005", name: "Tamara Malević" },
{ number: "R00007", name: "Dijana Šegina" },
];

var intervalId; // Holds the interval ID for selecting a number
var selectedCount = 0; // Keeps track of the number of selections
var selectedNumbers = []; // Array to store the selected numbers

function startGenerating() {
  intervalId = setInterval(selectNumber, 100); // Select a new number every 100 milliseconds
  
  document.getElementById("startButton").disabled = true;
}

var availableNumbers = possibleNumbers.slice(); // Make a copy of the possibleNumbers array
var selectedNumbers = []; // Initialize an empty array to store selected numbers


function selectNumber() {
	
  if (availableNumbers.length === 0) {
    availableNumbers = possibleNumbers.slice(); // Reset the available numbers array
	selectedNumbers = []; // Reset the selected numbers array
  }

  var randomIndex = Math.floor(Math.random() * availableNumbers.length);
  var selectedNumber = availableNumbers[randomIndex].number;
  var selectedName = availableNumbers[randomIndex].name;

  // Remove the selected number from availableNumbers array
  availableNumbers.splice(randomIndex, 1);
  
  // Store the selected number to avoid repetition
  selectedNumbers.push(selectedNumber);

  // Filter out the selected number from availableNumbers
  availableNumbers = availableNumbers.filter(function (item) {
    return item.number !== selectedNumber;
  });

  
  document.getElementById("randomNumber").textContent = selectedNumber; // Display the selected number
  document.getElementById("randomName").textContent = selectedName; // Display the associated name
}

function stopGenerating() {
  clearInterval(intervalId); // Stop the interval
  
  document.getElementById("startButton").disabled = false;
}

function saveToFile(filename, content) {
  var element = document.createElement("a");
  element.setAttribute(
    "href",
    "data:text/plain;charset=utf-8," + encodeURIComponent(content)
  );
  element.setAttribute("download", filename);
  element.style.display = "none";
  document.body.appendChild(element);
  element.click();
  document.body.removeChild(element);
}

function saveToFileAs(filename, content) {
  var sanitizedFilename = filename.replace(/[<>:"\/\\|?*\x00-\x1F]/g, ""); // Remove invalid characters from filename
  var element = document.createElement("a");
  element.setAttribute(
    "href",
    "data:text/plain;charset=utf-8," + encodeURIComponent(content)
  );
  element.setAttribute("download", sanitizedFilename);
  element.style.display = "none";
  document.body.appendChild(element);
  element.click();
  document.body.removeChild(element);
}

function checkFileExists(filename) {
  // Check if the list.txt file exists (replace with your own file check logic)
  // Assume it doesn't exist for demonstration purposes
  return false;
}

function saveToList() {
  if (selectedCount < 5) {
    selectedCount++;
    var selectedNumber = document.getElementById("randomNumber").textContent;
    var selectedName = document.getElementById("randomName").textContent;
    var listItem = document.createElement("li");
    listItem.textContent =
      getOrdinalNumber(selectedCount) +
      " award: " +
      selectedNumber +
      " - " +
      selectedName;
    document.getElementById("lastSelectedNumbers").appendChild(listItem);

    // Save the list to a file
    /*
	var listContent = document.getElementById("lastSelectedNumbers").textContent;
    var filename = "list.txt";
    var fileExists = checkFileExists(filename);

    if (fileExists) {
      saveToFile(filename, listContent);
    } else {
      saveToFileAs(filename, listContent);
    }
	*/
  }
}

function clearList() {
  var numbersList = document.getElementById("lastSelectedNumbers");
  numbersList.innerHTML = ""; // Clear the list
  selectedCount = 0; // Reset the selection count
}

function getOrdinalNumber(number) {
  var ordinalSuffix = "th";
  if (number === 1) {
    ordinalSuffix = "st";
  } else if (number === 2) {
    ordinalSuffix = "nd";
  } else if (number === 3) {
    ordinalSuffix = "rd";
  }
  return number + ordinalSuffix;
}



// Button click event handlers
document.getElementById("startButton").addEventListener("click", startGenerating);
document.getElementById("stopButton").addEventListener("click", stopGenerating);
document.getElementById("saveButton").addEventListener("click", saveToList);
document.getElementById("clearButton").addEventListener("click", clearList);



     
	
    </script>
</body>
</html>

@endsection

