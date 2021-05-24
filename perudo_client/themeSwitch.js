var theme = 0;
function themeSwitch(){
    if(theme == 0){
        document.getElementById("theme").setAttribute("href", "../light_theme.css");
        try{
            document.getElementById("eventGetter").src = "eventGetter.php?style=../light_theme.css";
        }
        catch{
            
        }
        document.cookie = "theme=../light_theme.css; expires=86400000; path=/";
        theme++;
    }
    else{
        document.getElementById("theme").setAttribute("href", "../dark_theme.css");
        try{
            document.getElementById("eventGetter").src = "eventGetter.php?style=../dark_theme.css";
        }
        catch{
            
        }
        document.cookie = "theme=../dark_theme.css; expires=86400000; path=/";
        theme--;
    }
}