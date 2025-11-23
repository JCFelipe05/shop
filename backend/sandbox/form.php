<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <p><b>Start typing a name in the input field below:</b></p>
    <form action="">
        <label for="fname">First name:</label>
        <input type="text" id="fname" name="fname" onkeyup="showHint(this.value)">
    </form>
    <p>Suggestions: </p>
    <div id="txtHint"></div>

    
    <script>
        function showHint(str) {
            if (str.length == 0) {
                document.getElementById("txtHint").innerHTML = "";
                return;
            } else {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("txtHint").innerHTML = this.responseText;
                    }
                };
                xmlhttp.open("GET", "db.php?productname=" + str, true);
                xmlhttp.send();
            }
        }
    </script>
</body>
</html>