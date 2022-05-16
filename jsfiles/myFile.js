function myfunction(id) {
  document.getElementById('delete_id').value=id;
  console.log(id);
}

function delete_post() {
  
  var id=document.getElementById('delete_id').value;
  var request = new XMLHttpRequest();
  request.open("GET", "../helping_php/deletepost.php?id="+id, true);
  request.send();
  request.onreadystatechange = function()
  {
    if (this.readyState == 4 && this.status == 200) {
     window.location= '../allpages/profilepost.php';
    }
  };
}



