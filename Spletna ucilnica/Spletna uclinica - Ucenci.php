<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spletna učilnica</title>
    <link rel='stylesheet' type='text/css' media='screen' href='Spletna ucilnica CSS.css'>
    <script src='main.js'></script>
</head>
<body>
<header>
        <ul id="headerList">
            <li><img id="logoPic"src="Slike/book.png"></li>
            <li id="headerText">SPLETNA UČILNICA</li>
            <li><img style="height: 80px;margin: 10% 0 0 0;"src="Slike/user.png"></li>
        </ul>
</header>

    <div class="outerDiv">
        <div class="navigationDiv">
            <ul id="navigationList">
                <li onclick="SelectedItem(this)" style="background-color:grey;">ZA PREGLED</li>
                <li onclick="SelectedItem(this)">PREDMETI</li>
            </ul>
        </div>
        
        <script>
            var selectedItemText = "UČITELJI";
            function SelectedItem(element){
                UnselectElements(element);
                if(element.innerText !== selectedItemText){
                    selectedItemText = element.innerText;
                    element.style.backgroundColor = "grey";
                }
            }
        
            function UnselectElements(selectedElement){
                const nav = document.getElementById("navigationList").children;
                for (let child of nav) {
                    if (child !== selectedElement) {
                        child.style.backgroundColor = "lightgrey";
                    }
                }
            }
        </script>
        <div class="optionGrid">
            <div class="gridItem"></div>
            <div class="gridItem"></div>
            <div class="gridItem"></div>
            <div class="gridItem"></div>
            <div class="gridItem"></div>
            <div class="gridItem"></div>
            <div class="gridItem"></div>
            <div class="gridItem"></div>
        </div>
    </div>
</body>
</html>