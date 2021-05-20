$(document).ready(function () {
    $.ajax({
        url: accepter2,
        type: "post",
        data: {
            _token: $(document).find("meta[name=csrf-token]").attr("content"),
            op: "afficher",
        },
        dataType: "json",
        success: function (data) {
            remplir($("#content-demande"), data);
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
            '<tr><th scope="row"><input type="checkbox" name="demande" value=""> &nbsp ' +
            myData[i].id +
            "</th>";
        ligne += "<td> " + myData[i].demande + "</td>";
        ligne += "<td> " + myData[i].nom + "</td>";
        ligne += "<td> " + myData[i].prenom + "</td>";
        myData[i].Date_livraison
            ? (ligne += "<td> " + myData[i].Date_livraison + "</td>")
            : (ligne += "<td> " + "en cours" + "</td>");
        ligne += "<td> " + myData[i].updated_at + "</td>";
        ligne += "<td> " + myData[i].etat + "</td></tr>";
    }

    selector.html(ligne);
}
