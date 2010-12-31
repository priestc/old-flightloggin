function front_page_toggle(to_display)
{
	if(to_display == "intro")
	{
		document.getElementById("intro").style.display = "block";
		document.getElementById("faq").style.display = "none";
		document.getElementById("news").style.display = "none";
		document.getElementById("walk").style.display = "none";
	}

	if(to_display == "faq")
	{
		document.getElementById("intro").style.display = "none";
		document.getElementById("faq").style.display = "block";
		document.getElementById("news").style.display = "none";
		document.getElementById("walk").style.display = "none";
	}
	
	if(to_display == "news")
	{
		document.getElementById("intro").style.display = "none";
		document.getElementById("faq").style.display = "none";
		document.getElementById("news").style.display = "block";
		document.getElementById("walk").style.display = "none";
	}
	
	if(to_display == "walk")
	{
		document.getElementById("intro").style.display = "none";
		document.getElementById("faq").style.display = "none";
		document.getElementById("news").style.display = "none";
		document.getElementById("walk").style.display = "block";
	}
}
