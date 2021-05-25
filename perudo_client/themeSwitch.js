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
function test(i){
  if(i == 0){
      document.documentElement.style.setProperty('--mainbgcolor', 'black');
    document.documentElement.style.setProperty('--textcolor', 'white');
    document.documentElement.style.setProperty('--inputbgcolor', 'white');
    document.documentElement.style.setProperty('--inputbgcolorfocus', 'lightgray');
    document.documentElement.style.setProperty('--submitbghover', 'rgb(212, 212, 212)');
    document.documentElement.style.setProperty('--eventbgcolor', '#222222');
  }
  else{
      document.documentElement.style.setProperty('--mainbgcolor', 'white');
      document.documentElement.style.setProperty('--textcolor', 'black');
      document.documentElement.style.setProperty('--inputbgcolor', 'lightgray');
      document.documentElement.style.setProperty('--inputbgcolorfocus', 'gray');
      document.documentElement.style.setProperty('--submitbghover', 'rgb(179, 179, 179)');
      document.documentElement.style.setProperty('--eventbgcolor', '#F4F4F4');
  }
}