$(document).ready(function () {
    $.ajax({
        url: livreeDemande1,
        type: "post",
        data: {
            _token: $(document).find("meta[name=csrf-token]").attr("content"),
            op: "afficher",
        },
        dataType: "json",
        success: function (data) {
            console.log(data);
            remplir($("#content-demande"), data.livree);
            $(".display").DataTable();
        },
        error: function (error) {
            console.log(error);
        },
    });
});

function remplir(selector, myData) {
    var ligne = "";

    for (let i = 0; i < myData.length; i++) {
        ligne +=
            '<tr><th scope="row"><input type="checkbox" name="demande" value=""> ' +
            myData[i].id +
            "</th>";
        ligne += "<td> " + myData[i].demande + "</td>";
        ligne += "<td> " + myData[i].dateDemande + "</td>";
        ligne += "<td> " + myData[i].Date_livraison + "</td>";
        ligne += "<td> " + myData[i].message + "</td></tr>";
    }

    selector.html(ligne);

}
