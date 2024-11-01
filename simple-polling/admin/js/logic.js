jQuery(document).ready(function(){
jQuery('.update-names-and-titles').click(function(){
	//getting person and title 
	var firstPersonName		= jQuery('.first-name').val();
	var secondPersonName	= jQuery('.last-name').val();
	var pollingTitle		= jQuery('.title-name').val();
//sending person and title details
jQuery.ajax({
    type: "POST",
    url: ajaxurl,
    data: { action: 'update_person_names' , firstPersonName: firstPersonName,secondPersonName:secondPersonName, pollingTitle:pollingTitle  }
  }).done(function( msg ) {
         alert( "Data Saved: " + msg );
         location.reload();
});
});
//deleting current polling
jQuery('.delete-current-polling').click(function(){

//sending person and title details
jQuery.ajax({
    type: "POST",
    url: ajaxurl,
    data: { action: 'delete_polling'  }
  }).done(function( msg ) {
         alert( "Data Saved: " + msg );
         location.reload();
});
});

});