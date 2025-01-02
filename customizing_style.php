<!DOCTYPE html>
<html>
<head>
    <title>Customize Reading Styles</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            transition: all 0.3s ease;
        }
        .customization-panel {
            padding: 20px;
            background-color: #f4f4f4;
            border: 1px solid #ccc;
            max-width: 300px;
        }
        .color-palette {
            display: flex;
            gap: 5px;
            margin-bottom: 15px;
        }
        .color-palette div {
            width: 30px;
            height: 30px;
            border: 1px solid #ddd;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="customization-panel">
        <h3>Customize Reading Styles</h3>

        <label for="fontType">Font Type:</label>
        <select id="fontType">
            <option value="Arial, sans-serif">Arial</option>
            <option value="Times New Roman, serif">Times New Roman</option>
            <option value="Courier New, monospace">Courier New</option>
            <option value="Verdana, sans-serif">Verdana</option>
        </select><br><br>

        <label for="fontSize">Font Size:</label>
        <input type="number" id="fontSize" value="16" min="10" max="36"> px<br><br>

        <label for="fontColor">Font Color:</label>
        <div class="color-palette" id="fontColors">
            <div style="background-color: #000;" data-color="#000"></div>
            <div style="background-color: #f00;" data-color="#f00"></div>
            <div style="background-color: #0f0;" data-color="#0f0"></div>
            <div style="background-color: #00f;" data-color="#00f"></div>
            <div style="background-color: #ff0;" data-color="#ff0"></div>
            <div style="background-color: #ffa500;" data-color="#ffa500"></div>
            <div style="background-color: #800080;" data-color="#800080"></div>
        </div>

        <label for="bgColor">Background Color:</label>
        <div class="color-palette" id="bgColors">
            <div style="background-color: #fff;" data-color="#fff"></div>
            <div style="background-color: #000;" data-color="#000"></div>
            <div style="background-color: #f4f4f4;" data-color="#f4f4f4"></div>
            <div style="background-color: #add8e6;" data-color="#add8e6"></div>
            <div style="background-color: #faf0e6;" data-color="#faf0e6"></div>
            <div style="background-color: #e6e6fa;" data-color="#e6e6fa"></div>
            <div style="background-color: #98fb98;" data-color="#98fb98"></div>
        </div>
    </div>

    <div id="content" style="padding: 20px;">
        <p>This is a sample text for customization. You can change the font style, size, color, and background color to suit your preferences.</p>
    </div>

    <script>
        const fontType = document.getElementById('fontType');
        const fontSize = document.getElementById('fontSize');
        const fontColors = document.getElementById('fontColors');
        const bgColors = document.getElementById('bgColors');
        const content = document.getElementById('content');

        // Change font type
        fontType.addEventListener('change', () => {
            content.style.fontFamily = fontType.value;
        });

        // Change font size
        fontSize.addEventListener('input', () => {
            content.style.fontSize = `${fontSize.value}px`;
        });

        // Change font color
        fontColors.addEventListener('click', (event) => {
            const color = event.target.getAttribute('data-color');
            if (color) {
                content.style.color = color;
            }
        });

        // Change background color
        bgColors.addEventListener('click', (event) => {
            const color = event.target.getAttribute('data-color');
            if (color) {
                content.style.backgroundColor = color;
            }
        });
    </script>
</body>
</html>
