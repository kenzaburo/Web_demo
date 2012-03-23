/* +++++++++++++++++++++++++++++++++++++++
*	Stagly Soft
*	Author: phuong.ho
*	Description: C_004_05(pcms admin  file)
*	Module: C_004_05
*	Date started: 30-09-2009
*	Date updated:
*	Module Version Number: 1.0.0
* +++++++++++++++++++++++++++++++++++++++ */
var Orelax = Class.create();
Orelax.prototype = {
    gPage : 1,
    gFileRow : "",
    gArrayS : "",
	gImgPath : "",
    initialize: function(path) {
		this.gImgPath = path;
	},

    /* +++++++++++++++++++++++++++++++++++++++
	*     Funtion Name: stl_Update()
	*     Description:
	*     Input Parameter:
	*     Output:
	* +++++++++++++++++++++++++++++++++++++++ */
    stl_AddNew : function(){
        var name = $F('name_Add').strip();
		var content = $F('content_Add').strip();
		
        if("" == name){
            alert("Vui lòng nhập vào tên tiêu đề thư giản!");
            return;
        }
		
		var arrInfo = new Array();
		arrInfo.push(name);
		arrInfo.push(content);
        arrInfo = arrInfo.toJSON();
        var url = "../admin/adminrelax.php";
        new Ajax.Request(url,{
            method:"POST",
            parameters:{arrInfo:arrInfo, p:5},
            onSuccess:function(Trans)
            {
                var rs = Trans.responseText.strip();
                if("2" == rs){
                    alert("Bạn đã thêm tiêu đề thư giản thành công!");
                    Orelax.stl_Paging(Orelax.gPage);
					Orelax.stl_CancelEdit();
                }else if("3" == rs){
					alert("tiêu đề thư giản này đã tồn tại!");
				}
            },
            onFailure:function()
            {
                alert("Vui lòng thử lại sau giây lát!");
				return;
            }
        });
       
        
    },
   
    /* +++++++++++++++++++++++++++++++++++++++
	*     Funtion Name: stl_CancelEdit()
	*     Description: hide edit screen
	*     Input Parameter:
	*     Output:
	* +++++++++++++++++++++++++++++++++++++++ */
    stl_CancelEdit : function(){
        this.gSWFOpened = false;
        if(null != $('C_004_05_Update')) $('C_004_05_Update').hide();

    },
    /* +++++++++++++++++++++++++++++++++++++++
	*     Funtion Name: stl_showAddNew()
	*     Description: show add new screen
	*     Input Parameter:
	*     Output:
	* +++++++++++++++++++++++++++++++++++++++ */
    stl_showAddNew:function(event){
        this.gFileRow = "";
        var posx = Event.pointerX(event) + 20;
		var posy = Event.pointerY(event) ;
        $('C_004_05_Update').setStyle(
        {
            left: posx + "px",
            top: posy + "px"
        });
		var url = "../admin/adminrelax.php";
        new Ajax.Request(url,{
            method:"POST",
            parameters:{p:7},
            onSuccess:function(Trans)
            {
                  var rs = Trans.responseText;
                  $('C_004_05_Update').innerHTML = rs;
                  $('C_004_05_Update').show();
				  $('C_004_05_txtPhotoName').value ="";	
            }
        });

    },
     
	/* +++++++++++++++++++++++++++++++++++++++
	*     Funtion Name: stl_showUpdateUser()
	*     Description: show edit screen
	*     Input Parameter:
	*     Output:
	* +++++++++++++++++++++++++++++++++++++++ */
    stl_showUpdateUser:function(ID, event){
        this.gFileRow = ID;
        var posx = Event.pointerX(event) + 20;
		var posy = Event.pointerY(event) ;
        $('C_004_05_Update').setStyle(
        {
            left: posx + "px",
            top: posy + "px"
        });
		var url = "../admin/adminrelax.php";
        new Ajax.Request(url,{
            method:"POST",
            parameters:{ID:ID, p:6},
            onSuccess:function(Trans)
            {
                  var rs = Trans.responseText;
                  $('C_004_05_Update').innerHTML = rs;
                  $('C_004_05_Update').show();
				  
					
            }
        });

    },
    /* +++++++++++++++++++++++++++++++++++++++
	*     Funtion Name: stl_Update()
	*     Description: update Orelax information
	*     Input Parameter:
	*     Output:
	* +++++++++++++++++++++++++++++++++++++++ */
    stl_Update : function(){
		if("" == this.gFileRow){
			this.stl_AddNew();
			return;
		}
        var ID  = this.gFileRow;
        var name = $F('name_Add').strip();
		var content = $F('content_Add').strip();
        if("" == name){
            alert("Vui lòng nhập vào tên tiêu đề thư giản!");
            return;
        }
		
		var arrInfo = new Array();
		arrInfo.push(name);
		arrInfo.push(content);
        arrInfo = arrInfo.toJSON();
        var url = "../admin/adminrelax.php";
        new Ajax.Request(url,{
            method:"POST",
            parameters:{ID:ID, arrInfo : arrInfo, p:1},
            onSuccess:function(Trans)
            {
                var rs = Trans.responseText.strip();;
                if("OK" == rs){
                    alert("Bạn đã cập nhật thông tin thành công!");
                    Orelax.stl_CancelEdit();
                    Orelax.stl_Paging(Orelax.gPage);
                }else if("NG" == rs){
                    alert("Thông tin tiêu đề thư giản không thay đổi!");
					Orelax.stl_CancelEdit();
                }else{
					alert("Tên tiêu đề thư giản này đã tồn tại!");
				}
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
        $('C_004_05_Maincontent').innerHTML = "<div align='center' style='padding-top:10px;'><img src='../../C_004_05/img/icon_loading_pops.gif'/></div>";
        var url = "../admin/adminrelax.php";
        new Ajax.Request(url,{
            method:"POST",
            parameters:{Page:Page, p:2},
            onSuccess:function(Trans)
            {
                  var rs = Trans.responseText.evalJSON();
                  $('C_004_05_Maincontent').innerHTML = rs['content'];
                  $('C_004_05_paging').innerHTML = rs['paging'];

            }
        });
        this.stl_CancelEdit();
    },

    /* +++++++++++++++++++++++++++++++++++++++
	*     Funtion Name: stl_reset()
	*     Description: return the first state
	*     Input Parameter:
	*     Output:
	* +++++++++++++++++++++++++++++++++++++++ */
    stl_reset : function(){
        this.stl_Paging(1);
    },
    /* +++++++++++++++++++++++++++++++++++++++
	*     Funtion Name: stl_deleteUser()
	*     Description: delete user
	*     Input Parameter:
	*     Output:
	* +++++++++++++++++++++++++++++++++++++++ */
    stl_deleteUser:function(ID, IsDeleted){
		if(1 == IsDeleted){
                isContinue =  confirm('Bạn thực sự muốn xoá tiêu đề thư giản này?');
                if(!isContinue) return;
        }
        var url = "../admin/adminrelax.php";
        new Ajax.Request(url,{
            method:"POST",
            parameters:{ID:ID, IsDeleted:IsDeleted, p:3},
            onSuccess:function(Trans)
            {
                  var rs = Trans.responseText.strip();;
                  if("OK" == rs){
                    if(1 == IsDeleted){
                        alert("Bạn đã xoá tiêu đề thư giản thành công!");
                    }else{
                        alert("Bạn đã phục hồi tiêu đề thư giản thành công!");
                    }
                    Orelax.stl_Paging(Orelax.gPage);
                  }else{
					alert("Vui lòng thử lại sau giây lát!");
				  }
            }
        });
    },
    /* +++++++++++++++++++++++++++++++++++++++
	*     Funtion Name: stl_Search()
	*     Description: search
	*     Input Parameter:
	*     Output:
	* +++++++++++++++++++++++++++++++++++++++ */
    stl_Search : function(){
        var nameS       = $F('Gname_search').strip();
        var contentS      = $F('Gcontent_search').strip();
        var fromdateS   = $F('GFromDate').strip();
        var todateS     = $F('GToDate').strip();
        if(nameS == "" && contentS == "" && (fromdateS == "" || todateS == "")){
            alert("Vui lòng nhập vào thông tin tìm kiếm!");
			return;
        }
        var arrInfo = new Array();
		arrInfo.push(nameS);
		arrInfo.push(contentS);
		arrInfo.push(fromdateS);
		arrInfo.push(todateS);
        arrInfo = arrInfo.toJSON();
        this.gArrayS =   arrInfo;
        $('C_004_05_Maincontent').innerHTML = "<div align='center' style='padding-top:10px;'><img src='../../C_004_05/img/icon_loading_pops.gif'/></div>";
        var url = "../admin/adminrelax.php";
        new Ajax.Request(url,{
            method:"POST",
            parameters:{arrInfo:arrInfo, p:4},
            onSuccess:function(Trans)
            {
                var rs = Trans.responseText.evalJSON();
                $('C_004_05_Maincontent').innerHTML = rs['content'];
                $('C_004_05_paging').innerHTML = rs['paging'];
            }
        });
        
        this.stl_CancelEdit();
    },
    /* +++++++++++++++++++++++++++++++++++++++
	*     Funtion Name: stl_PagingS()
	*     Description: paging search
	*     Input Parameter:
	*     Output:
	* +++++++++++++++++++++++++++++++++++++++ */
    stl_PagingS : function(Page){
        $('C_004_05_Maincontent').innerHTML = "<div align='center' style='padding-top:10px;'><img src='../../C_004_05/img/icon_loading_pops.gif'/></div>";
        var url = "../admin/adminrelax.php";
        new Ajax.Request(url,{
            method:"POST",
            parameters:{Page:Page, arrInfo:this.gArrayS, p:4},
            onSuccess:function(Trans)
            {
                  var rs = Trans.responseText.evalJSON();
                  $('C_004_05_Maincontent').innerHTML = rs['content'];
                  if(rs['content'].strip() == "")
                  $('C_004_05_Maincontent').innerHTML = "Không tìm thấy dữ liệu nào!";
                  $('C_004_05_paging').innerHTML = rs['paging'];
            }
        });
        this.stl_CancelEdit();
    }
};
