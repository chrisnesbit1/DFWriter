<html>
<head>
<title>DFWriter | Distraction Free Writer</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<style>
html, body {
  font: 16pt arial, sans-serif;
  border: 0;
  margin: auto;
  width:100%;
  height:100%;
  box-sizing: border-box;
  -moz-box-sizing:border-box;
  -webkit-box-sizing:border-box;
}
h3 {
  padding: 0;
  margin: 0;
}
#filenamecontainer {
  position: absolute;
  top:0px;
  left: 50%;
  width: 50%;
}
#filename {
  text-align: center;
  color: lightgray;
  position: relative;
  left: -50%;
  width: 100%;
  height: 50px;
}
textarea {
  padding:50px 50px 55px;
  font: 16pt arial, sans-serif;
  width: 100%;
  height:100%;
  outline:0px !important;
  -webkit-appearance:none;
}
#controlpanel {
  position: absolute;
  top: 50px;
  right: 0px;
  text-align: center;
  width: 50px;
}
#controlpanel i {
  padding: 3px;
}
img {
  position: absolute;
  bottom: 0px;
  right: 0px;
  max-width: 200px;
}
</style>
</head>
<body>
<div id="filenamecontainer" class="hideable">
  <div id="filename"></div>
</div>
<textarea tabindex="1" placeholder="this is some starter text" autofocus></textarea>
<div id='controlpanel' class="hideable">
  <i title="New File" class="fa fa-file-text-o"></i>
  <i title="Open File with Dropbox" class="fa fa-folder-open"></i><br/>
  <i title="Save File to Dropbox" class="fa fa-floppy-o"></i><br/>
</div>
<a class="hideable" href="https://db.tt/ciiLfxNR"><img src="dropbox-logos_powered-by-dropbox.png" /></a>
</body>
</html>

<script type="text/javascript" src="https://www.dropbox.com/static/api/2/dropins.js" id="dropboxjs" data-app-key="9744arcr8j1kokv"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<script type="text/javascript">
var textarea = $('textarea'),
    hideable = $('.hideable'),
    controlpanel = $('#controlpanel'),
    btnnew = $('.fa-file-text-o'),
    btnopen = $('.fa-folder-open'),
    btnsave = $('.fa-floppy-o'),
    dropboxlogo = $('img'),
    timeinterval = 5 * 1000,
    timer;

var starttyping = function() {
  clearTimeout(timer);
    hideable.fadeOut();
};

var fadeinHideables = function() {
  hideable.fadeIn();
};

var stoptyping = function() {
  clearTimeout(timer);
  timer = setTimeout(function(){
    fadeinHideables();
  }, timeinterval);
};

var newfile = function() {
  $('#filename').html('');
  textarea.val('');
};

var openfile = function() {
  var options = {
    success: function(files) {
        var filename = decodeURIComponent(files[0].link.replace("https://www.dropbox.com/s/", "").replace("?dl=0",""));
	var filenameparts = filename.split("/");
	$('#filename').html("<h3>" + filenameparts[filenameparts.length-1] + "</h3>");
        //https://www.dropbox.com/s/deqyi4705rdqf7k/peedeegeek.com.txt?dl=0
        $.get('https://dl.dropboxusercontent.com/1/view/'+filename, function (data) {
                textarea.val(data); // <-- this will log the contents of the file
        });
    },
    cancel: function() {

    },
    linkType: "preview", // or "direct"
    multiselect: false, // or true
    extensions: ['.txt']
  };
  Dropbox.choose(options);
};

var savefile = function(e, flname) {

  // if not set by user prompt, so check the filename heading
  if (typeof(flname) == 'undefined') {
    flname = $('#filename').html().replace('<h3>','').replace('</h3>','');
  }

  // if not set by filename heading either, ask the user
  if (typeof(flname) == 'undefined' || flname.length < 1) {
    bootbox.prompt({
      title: "Please specify a filename:",
      value: "",
      callback: function(result) {
        if (result === null) {
          bootbox.alert("Save Cancelled.");
        } else {
          savefile(e, result);
        }
      }
    });
    return;
  }

  var options = {
    files: [
        {'url': "data:text/plain;charset=utf-8," + encodeURIComponent(textarea.val()), 'filename': flname}
    ],

    // Success is called once all files have been successfully added to the user's
    // Dropbox, although they may not have synced to the user's devices yet.
    success: function () {
        // Indicate to the user that the files have been saved.
        bootbox.alert("Success! File saved to your Dropbox.");
    },

    // Progress is called periodically to update the application on the progress
    // of the user's downloads. The value passed to this callback is a float
    // between 0 and 1. The progress callback is guaranteed to be called at least
    // once with the value 1.
    progress: function (progress) {},

    // Cancel is called if the user presses the Cancel button or closes the Saver.
    cancel: function () {},

    // Error is called in the event of an unexpected response from the server
    // hosting the files, such as not being able to find a file. This callback is
    // also called if there is an error on Dropbox or if the user is over quota.
    error: function (errorMessage) {
      bootbox.alert(errorMessage);
    }
  };
  Dropbox.save(options);
};

textarea.keydown(starttyping);
textarea.keyup(stoptyping);
btnnew.click(newfile);
btnopen.click(openfile);
btnsave.click(savefile);
$(window).mousemove(fadeinHideables);

</script>
