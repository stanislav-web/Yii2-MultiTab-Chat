function getMessages() {

    jQuery.ajax({
        url: listUrl,
        type: 'post',
        data: {searchname: $("#searchname").val() , searchby:$("#searchby").val()},
        success: function (data) {
            alert(data);

        }
    });
}

function postMessage(userName, userIP, message) {
    console.log(userName, userIP, message)
}
