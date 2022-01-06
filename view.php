<!DOCTYPE html>
<html>

<head>
    <title>View</title>
    <style>
        .form {
            width: 500px;
            margin: 0 auto;
            padding: 10px;
            border: 1px solid #ccc;
        }

        .form h4 {
            color: #0099cc;
        }

        .form-group {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form">
            <h4>Form Interface To Post,Put and Delete</h4>
            <form id ="mainForm">
                <div class="form-group">
                    <label for="method">Action: </label>
                    <input type="radio" name="action" value="post" checked>Post
                    <input type="radio" name="action" value="put" checked>Put
                    <input type="radio" name="action" value="delete" checked>Delete
                </div>
                <div class="form-group">
                    <label for="name">Currency: </label>
                    <select name="curr" id="curr">
                        <option value="USD">USD</option>
                        <option value="EUR">EUR</option>
                        <option value="GBP">GBP</option>
                        <option value="AUD">AUD</option>
                        <option value="CAD">CAD</option>
                        <option value="CHF">CHF</option>
                        <option value="CNY">CNY</option>
                        <option value="DKK">DKK</option>
                        <option value="HKD">HKD</option>
                        <option value="INR">INR</option>
                        <option value="JPY">JPY</option>
                        <option value="MXN">MXN</option>
                        <option value="NZD">NZD</option>
                        <option value="PLN">PLN</option>
                        <option value="RUB">RUB</option>
                        <option value="SEK">SEK</option>
                        <option value="SGD">SGD</option>
                        <option value="THB">THB</option>
                        <option value="TRY">TRY</option>
                        <option value="ZAR">ZAR</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="submit" name="Submit">
                </div>

            </form>

            <div class='response-div'>
                <textarea id="responseDiv" rows="10" cols="50"></textarea>
            </div>
        </div>
    </div>
    <script src="./assets/script.js"></script>
</body>

</html>