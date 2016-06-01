
// function for clearing the form fields and removing any error/success messages
function clearPage() {
  
    var blogIdField = document.getElementById('BlogId');  

    // unselect blog if selected
    if (blogIdField.value != '') {
        var id = 'Blog' + blogIdField.value;
        try {
            document.getElementById(id).className = 'blog'; // gets rid of "selected" class
        }
        catch (err) {
            // probably just deleted the selected blog
        }
    }

    // clear form fields
    blogIdField.value = '';
    document.getElementById('Title').value = '';
    document.getElementById('Body').value = '';
    document.getElementById('Mood').selectedIndex = 0;

    // set focus to name field
    document.getElementById('Title').focus();

    // remove output h4 tags if they're there
    try {
        var headerTag = document.getElementsByTagName('h4')[1];
        var parent = headerTag.parentNode;
        parent.removeChild(headerTag);
    }
    catch (err) { 
        // no <h4> to remove             
    }
}

function setAction(cmd, id) {
    document.getElementById("ActionTextBox").value = cmd;
    if (cmd === 'delete') {
        var youSure = confirm('Are you sure you want to delete this blog?');
        if (!youSure) { return; }
    }
    if (cmd !== 'save') {
        // user clicked edit or delete...set id field so we know what blog we're dealing with
        document.getElementById('BlogId').value = id;
    }
    document.getElementById('MyForm').submit();
}

function deleteComment(id) {
    var youSure = confirm('Are you sure you want to delete this comment?');
    if (youSure) {
        document.getElementById('CommentIdTextBox').value = id;
        return true;
    }
    else {
        return false;
    }
}