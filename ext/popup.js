chrome.tabs.getSelected(null,function(tab) {
	var tablink = tab.url;
	document.getElementById('myFrame').src = 'http://altercomments.ebnes.ir/url/'+(tablink);
});