function tableGenrator (names, cubes){
    var table = document.createElement("table");
    for(let i = 0; i < names.length; i++){
        var row = document.createElement("tr");
        var tdname = document.createElement("td");
        var tdcube = document.createElement("td");
        var textcube = document.createTextNode(cubes[i]);
        var textname = document.createTextNode(names[i]);
        tdname.appendChild(textname);
        tdcube.appendChild(textcube);
        row.appendChild(tdname);
        row.appendChild(tdcube);
        table.appendChild(row);
    }
    return table;
}
function arraymaker(newarray){ //makes array from php string output
    newarray = newarray.replace("[", "");
    newarray = newarray.replace("]", "");
    newarray = newarray.replaceAll('"', "");
    newarray = newarray.split(",");
    return newarray;
}