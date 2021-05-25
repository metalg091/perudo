//var theme = 1;
/*function themeSwitchOld(){
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
}*/
function themeSwitch(){
  if(theme == 0){
    document.documentElement.style.setProperty('--mainbgcolor', 'black');
    document.documentElement.style.setProperty('--textcolor', 'white');
    document.documentElement.style.setProperty('--inputbgcolor', 'white');
    document.documentElement.style.setProperty('--inputbgcolorfocus', 'lightgray');
    document.documentElement.style.setProperty('--submitbghover', 'rgb(212, 212, 212)');
    document.documentElement.style.setProperty('--eventbgcolor', '#222222');
    theme++;
    document.cookie = "theme=" + theme + "; expires=86400000; path=/";
  }
  else{
    document.documentElement.style.setProperty('--mainbgcolor', 'white');
    document.documentElement.style.setProperty('--textcolor', 'black');
    document.documentElement.style.setProperty('--inputbgcolor', 'lightgray');
    document.documentElement.style.setProperty('--inputbgcolorfocus', 'gray');
    document.documentElement.style.setProperty('--submitbghover', 'rgb(179, 179, 179)');
    document.documentElement.style.setProperty('--eventbgcolor', '#F4F4F4');
    theme--;
    document.cookie = "theme=" + theme + "; expires=86400000; path=/";
  }
  try{
    document.getElementById('eventGetter').src = 'eventGetter.php';
    document.getElementById('theme').href = '../dark_theme.css';
  }
  catch{
    
  }
}
function themeSetup(){
  if(theme == 0){
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
