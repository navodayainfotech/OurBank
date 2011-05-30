
/*
############################################################################
#  This file is part of OurBank.
############################################################################
#  OurBank is free software: you can redistribute it and/or modify
#  it under the terms of the GNU Affero General Public License as
#  published by the Free Software Foundation, either version 3 of the
#  License, or (at your option) any later version.
############################################################################
#  This program is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU Affero General Public License for more details.
############################################################################
#  You should have received a copy of the GNU Affero General Public License
#  along with this program.  If not, see <http://www.gnu.org/licenses/>.
############################################################################
*/
/**to select the value of sub office when there is any changes in office type*/					


    function Getsubcaste(casteid,path) {
        if(casteid) {
        targeturl=path+"/familydefault/index/getsubcaste?casteid="+casteid; 
        $.ajax({ url: targeturl, success: function(data){ $(".subcaste").html(data) }});
        }
    }
    function gethabitationDetails(path,rev_villageid) {
    if(rev_villageid) { 
            targeturl=path+"/familydefault/index/gethabitation?rev_village="+rev_villageid; 
            $.ajax({ url: targeturl, success: function(data){ $("#village").html(data) }});
    }
    }

    $(function(){
    $(".child").click ( function() {
        var count=$("#count").val();
        if($("#health-1").is(':checked')) {
            for( i=2; i <= count; i++){
                $("#health-"+i).attr ( "disabled" , true );
            }
        } else {
            for( i=2; i <= count; i++){
                $("#health-"+i).attr ( "disabled" , false );
            }
        }
    });
    });