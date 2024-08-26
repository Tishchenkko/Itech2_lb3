<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work</title>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('fetchDataButton').addEventListener('click', function() {
                var managerName = document.getElementById('managerName').value;

                fetch(`workers.php?manager_name=${encodeURIComponent(managerName)}`)
                    .then(response => response.json())
                    .then(data => {
                        // Display all formats
                        document.getElementById('plainText').innerText = 'Plain Text:\n' + data.plain_text;
                        document.getElementById('json').innerText = 'JSON:\n' + data.json;
                        document.getElementById('xml').innerText = 'XML:\n' + data.xml;
                    })
                    .catch(error => {
                        document.getElementById('result').innerText = `Error: ${error.message}`;
                    });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('fetchTotalTimeButton').addEventListener('click', function() {
                var managerName = document.getElementById('projectNameTime').value;

                fetch(`projects.php?project_name=${encodeURIComponent(managerName)}`)
                    .then(response => response.json())
                    .then(data => {
                        // Display all formats
                        document.getElementById('plainTextTime').innerText = 'Plain Text:\n' + data.plain_text;
                        document.getElementById('jsonTime').innerText = 'JSON:\n' + data.json;
                        document.getElementById('xmlTime').innerText = 'XML:\n' + data.xml;
                    })
                    .catch(error => {
                        document.getElementById('resultTime').innerText = `Error: ${error.message}`;
                    });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('fetchProjectTimeButton').addEventListener('click', function() {
                var managerName = document.getElementById('projectTimeName').value;
                var date = document.getElementById('dateInput').value;

                fetch(`tasks.php?project_name=${encodeURIComponent(managerName)}
                                    &date=${encodeURIComponent(date)}`)
                    .then(response => response.json())
                    .then(data => {
                        // Display all formats
                        document.getElementById('plainTextProjectTime').innerText = 'Plain Text:\n' + data.plain_text;
                        document.getElementById('jsonProjectTime').innerText = 'JSON:\n' + data.json;
                        document.getElementById('xmlProjectTime').innerText = 'XML:\n' + data.xml;
                    })
                    .catch(error => {
                        document.getElementById('resultProjectTime').innerText = `Error: ${error.message}`;
                    });
            });
        });
    </script>
    <style>
        header {
            background-color: #FFBF00;
            padding: 1rem;
            text-align: center;
            color: white;
        }

        .container {
            display: flex;
            justify-content: space-around;
            border: 2px solid #ccc;
            padding: 15px;
            margin-top: 30px;
        }

        .tasks {
            width: 300px;
            min-height: 250px;
            background-color: #f0f0f0;
            margin: 10px;
            font-size: 18px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            justify-content: center;
            overflow-wrap: break-word;
        }

        .button {
            padding: 8px 16px;
            background-color: #E49B0F;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            display: inline-block;
            border: none;
        }

        pre {
          width: 300px;
          font-size: 14px;
          text-align: left;

          white-space: normal;
        }
        
    </style>
</head>

<body>
    <header>
        <!-- Removed the lab title -->
    </header>
    <div class="container">
        <div class="tasks">
            <p style="padding-top: 20px;">The number of subordinates of each chief</p>
                <label for="name">Chief name: </label>
                <select id="managerName" name="manager_name">
                    <option value="Jobs">Jobs</option>
                    <option value="Wozniak">Wozniak</option>
                    <option value="Gates">Gates</option>
                </select>
                <button id="fetchDataButton" class="button">Enter</button>
                <pre id="plainText"></pre>
                <pre id="json"></pre>
                <pre id="xml"></pre>
                <pre id="result"></pre>
        </div>
        <div class="tasks">
            <p style="padding-top: 20px;">Total time spent on the selected project</p>
                <label for="name">Project name: </label>
                <select id="projectNameTime" name="project_name">
                    <option value="Project_1, Hospital">Hospital</option>
                    <option value="Project_2, Bank">Bank</option>
                    <option value="Project_3, Police">Police</option>
                </select>
                <button id="fetchTotalTimeButton" class="button">Enter</button>
                <pre id="plainTextTime"></pre>
                <pre id="jsonTime"></pre>
                <pre id="xmlTime"></pre>
                <pre id="resultTime"></pre>

        </div>
        <div class="tasks">
            <p style="padding: 20px 10px;">Information on completed tasks for the specified project on the selected date</p>
                <label for="project">Project name:</label>
                <select id="projectTimeName" name="project_name">
                    <option value="Project_1, Hospital">Hospital</option>
                    <option value="Project_2, Bank">Bank</option>
                    <option value="Project_3, Police">Police</option>
                </select><br>
                <label for="dateInput">Enter date:</label>
                <input type="date" id="dateInput" name="date"><br><br>
                <button id="fetchProjectTimeButton" class="button">Enter</button>
                <pre id="plainTextProjectTime"></pre>
                <pre id="jsonProjectTime"></pre>
                <pre id="xmlProjectTime"></pre>
                <pre id="resultProjectTime"></pre>
        </div>
    </div>
</body>

</html>
