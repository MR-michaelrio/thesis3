<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <style>
        /* Container for the iframe with clipping */
        .iframe-container {
            width: 450px;  /* Set the width to match the desired size */
            height: 790px; /* Set the height to match the desired size */
            overflow: hidden; /* Hide content outside the iframe container */
            position: relative; /* Ensures content inside iframe is positioned correctly */
        }

        /* Styling the iframe to fit within the container */
        iframe {
            width: 100%;
            height: 100%;
            border: none; /* Remove border */
            position: absolute; /* Position content inside iframe */
            top: -190px; /* Adjust this value to crop content vertically */
        }
    </style>
</head>
<body>
    <div class="iframe-container">
        <iframe 
            src="https://business.facebook.com/wa/manage/flows/891327676362658/preview/?token=2c53cd08-f9be-4f76-bdb2-c00c82874322" 
            frameborder="0">
        </iframe>
    </div>
</body>
</html>
