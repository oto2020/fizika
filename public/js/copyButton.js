var button = document.getElementById('userButton');
button.addEventListener('click', function () {
    var range = document.createRange();
    range.selectNode(document.getElementById('cont'));
    window.getSelection().addRange(range);
    document.execCommand('copy');
    window.getSelection().removeAllRanges();
});
