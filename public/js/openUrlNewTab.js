var links = document.querySelectorAll('.post-body a');
for (var i = 0, length = links.length; i < length; i++) {
    if (links[i].hostname != window.location.hostname) {
        links[i].target = '_blank';
    }
}