var theme = 0;
function themeSwitch(){
    if(theme == 0){
        document.getElementById("theme").setAttribute("href", "light_theme.css");
        document.getElementById("eventGetter").src = "eventGetter.php?style=light_theme.css";
        theme++;
    }
    else{
        document.getElementById("theme").setAttribute("href", "dark_theme.css");
        document.getElementById("eventGetter").src = "eventGetter.php?style=dark_theme.css";
        theme--;
    }
}