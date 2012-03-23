/* +++++++++++++++++++++++++++++++++++++++
*	Stagly Soft
*	Author: phuong.ho
*	Description: C_004_05(pcms admin  file)
*	Module: C_004_05
*	Date started: 30-09-2009
*	Date updated:
*	Module Version Number: 1.0.0
* +++++++++++++++++++++++++++++++++++++++ */
var hp_user = Class.create();
hp_user.prototype = {
    gPage : 1,
    gPageR : 1,
    rootUrl : "",
    initialize: function(root) {
        this.rootUrl = root;
	},
    /* +++++++++++++++++++++++++++++++++++++++
	*     Funtion Name: stl_Paging()
	*     Description: paging
	*     Input Parameter:
	*     Output:
	* +++++++++++++++++++++++++++++++++++++++ */
    stl_PagingR : function(Page){
        this.gPageR = Page;
        $('All_content_second').innerHTML = "<div align='center' style='padding-top:10px;'><img src='../img/icon_loading_pops.gif'/></div>";
        var url = this.rootUrl + "htm/relax_detail.php";
        new Ajax.Request(url,{
            method:"POST",
            parameters:{Page:Page, p:1},
            onSuccess:function(Trans)
            {
                  var rs = Trans.responseText.evalJSON();
                  $('All_content_second').innerHTML = rs['content'];
                  $('All_content_paging').innerHTML = rs['paging'];
            }
        });
    },
    /* +++++++++++++++++++++++++++++++++++++++
	*     Funtion Name: stl_Paging()
	*     Description: paging
	*     Input Parameter:
	*     Output:
	* +++++++++++++++++++++++++++++++++++++++ */
    stl_Paging : function(Page){
        this.gPage = Page;
        $('All_content_second').innerHTML = "<div align='center' style='padding-top:10px;'><img src='../img/icon_loading_pops.gif'/></div>";
        var url = this.rootUrl + "htm/index.php";
        new Ajax.Request(url,{
            method:"POST",
            parameters:{Page:Page, p:1},
            onSuccess:function(Trans)
            {
                  var rs = Trans.responseText.evalJSON();
                  $('All_content_second').innerHTML = rs['content'];
                  $('All_content_paging').innerHTML = rs['paging'];
            }
        });
    },

    /* +++++++++++++++++++++++++++++++++++++++
	*     Funtion Name: stl_reset()
	*     Description: return the first state
	*     Input Parameter:
	*     Output:
	* +++++++++++++++++++++++++++++++++++++++ */
    stl_reset : function(){
        this.stl_Paging(1);
    }
};
