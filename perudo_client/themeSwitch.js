var theme = 0;
function themeSwitch(){
    if(theme == 0){
        document.getElementById("theme").setAttribute("href", "light_theme.css");3
        theme++;
    }
    else{
        document.getElementById("theme").setAttribute("href", "dark_theme.css");
        theme--;
    }
}