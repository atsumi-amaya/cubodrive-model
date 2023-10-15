function selectProc(){
    let proc = document.getElementById('proceso')
    let valorProc = proc.value;
    if (valorProc == 3) {
        document.getElementById('op').style.display='';
    } else {
        document.getElementById('op').style.display='none';
    }
}
function numTec(){
    let proc = document.getElementById('nTec')
    let valorProc = proc.value;
    if (valorProc == 2) {
        document.getElementById('tecname').textContent='LIDER GRUPO';
        document.getElementById('tec').style.display='';
    } else {
        document.getElementById('tecname').textContent='TECNICO';
        document.getElementById('tec').style.display='none';
    }
}
function registSede(){
    let proc = document.getElementById('sede')
    let valorProc = proc.value;
    if (valorProc == 2) {
        document.getElementById('adm').style.display='none';
        document.getElementById('admE').style.display='none';
        document.getElementById('sso').style.display='';
        document.getElementById('ssoE').style.display='';
    } else if (valorProc == 3){
        document.getElementById('adm').style.display='';
        document.getElementById('admE').style.display='';
        document.getElementById('sso').style.display='';
        document.getElementById('ssoE').style.display='';
    } else {
        document.getElementById('adm').style.display='';
        document.getElementById('admE').style.display='';
        document.getElementById('sso').style.display='none';
        document.getElementById('ssoE').style.display='none';
    }
}


