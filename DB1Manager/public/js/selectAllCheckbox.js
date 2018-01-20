/**
 * To select all checkboxes with an other checkbox
 *
 * @author mstu15
 * @version 20.01.2017
 */
$(function () {

    //button select all or cancel
    $("#select-all").click(function () {
        var all = $("input.select-all")[0];
        all.checked = !all.checked
        var checked = all.checked;
        $("input.select-item").each(function (index, item) {
            item.checked = checked;
        });
    });

    //button select invert
    $("#select-invert").click(function () {
        $("input.select-item").each(function (index, item) {
            item.checked = !item.checked;
        });
        checkSelected();
    });

    //button get selected info
    $("#selected").click(function () {
        var items = [];
        $("input.select-item:checked:checked").each(function (index, item) {
            items[index] = item.value;
        });
        if (items.length < 1) {
            alert("no selected items!!!");
        } else {
            var values = items.join(',');
            console.log(values);
            var html = $("<div></div>");
            html.html("selected:" + values);
            html.appendTo("body");
        }
    });

    //column checkbox select all or cancel
    $("input.select-all").click(function () {
        var checked = this.checked;
        $("input.select-item").each(function (index, item) {
            item.checked = checked;
        });
    });

    //check selected items
    $("input.select-item").click(function () {
        var checked = this.checked;
        console.log(checked);
        checkSelected();
    });

    //check is all selected
    function checkSelected() {
        var all = $("input.select-all")[0];
        var total = $("input.select-item").length;
        var len = $("input.select-item:checked:checked").length;
        console.log("total:" + total);
        console.log("len:" + len);
        all.checked = len === total;
    }
});