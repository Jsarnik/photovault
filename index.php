<?php  
//get unique id 
$up_id = uniqid();  
?> 

<!DOCTYPE html>
<html>
<head>
  <title>Angular Example - Photo Vault</title>
  <!-- INCLUDE REQUIRED THIRD PARTY LIBRARY JAVASCRIPT AND CSS -->
  <!--<script type="text/javascript" src="js/angular.min.js"></script>-->
  <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.4/angular.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.1/angular-route.min.js"></script>
  <script type="text/javascript" src="js/cms-app/app.js"></script>
  <script type="text/javascript" src="js/cms-app/controllers/mainController.js"></script>
  <script src="js/ui-bootstrap-0.12.1.min.js"></script>
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap-responsive.min.css">

<script type="text/javascript">

$(document).ready(function(){
  

});
</script>


</style>
</head>
<body ng-app="AngularCMS">
    <div class='banner'>
        <div class='container'>
          <div id="header-content">
            <h1>Photo Vault</h1><h3>(An Angular CMS)</h3>
            <div id="header-links">
              <div id="user_name"></div>
            </div>
          </div>
        </div>
    </div>
  <div class="container main-frame" ng-controller="mainController" ng-init="init(0)">
    <div class="search-box row-fluid form-inline">
      <label>Category: </label>
<input id="category" type="text" ng-model="filterCategory"/>
<label>Photo: </label>
<input id="item" type="text" ng-model="filterPhoto"/>
    </div>
        <div class="results-top"></div>
        <div class="results-container">
            <ul class="repeater-list">
      <div style="width: 100%; text-align:center;margin-bottom:10px;"><div class="button add" ng-click="open(results['sub-items'], 'subItem')" style="display: inline-block;">Category +</div></div>
      <li id="category-{{$index}}" class="category-container" ng-repeat="subItems in results['sub-items'] | filter: filterCategory track by $index">

        <div class="info-overlay" ng-class="{'include' : isInclude && selectedID == 'subItem-' + $parent.$index + '-' +  $index}"></div>

                <div id="subItem-{{$parent.$index}}-{{$index}}" class="category-header">
            				<div class="info-overlay"  ng-class="{'edit' : isEdit && selectedID == 'subItem-' + $parent.$index +  '-' + $index,'remove' : isInclude && selectedID == 'subItem-' + $parent.$index +  '-' + $index, 'move' : isMove && selectedID == 'subItem-' + $parent.$index +  '-' + $index}"></div>            		
            		<div class="align-header-buttons">	
                              <div ng-mouseover="hoverIn('include', 'subItem-' + $parent.$index +  '-' + $index);" class="button delete disableable" disable="isDisabled" ng-mouseleave="hoverOut('include');" >
                              	<div class="disableable" ng-click="remove(results['sub-items'], subItems);" ng-confirm-click="*WARNING* Deleting category ({{subItems.name}}) will delete all of it's images too?">&#9747;</div>
                              </div>

                              <div ng-mouseover="hoverIn('move', 'subItem-' + $parent.$index +  '-' + $index);" class="button up disableable" disable="isDisabled" ng-mouseleave="hoverOut('move');" ng-click="changePosition(results['sub-items'],$index, subItems,false)">&#9650;</div>

                            
                              <div ng-mouseover="hoverIn('move', 'subItem-' + $parent.$index +  '-' + $index);" class="button down disableable" disable="isDisabled" ng-mouseleave="hoverOut('move');" ng-click="changePosition(results['sub-items'],$index, subItems,true)">&#9660;</div>

                        <div ng-hide="isShow('subItem-'+ $parent.$index +  '-' +$index)">
                            <span>{{subItems.name}} </span><span>( {{subItems.images.length}} )</span>                            
                            <a class="edit" href="javascript:void(0)" ng-click="update(subItems, 'subItem-'+ $parent.$index +  '-' +$index);">Edit</a>
                            </div>
                        <div ng-show="isShow('subItem-'+ $parent.$index +  '-' +$index)">
                            <input ng-model="subItems.name"/>
                            <a href="javascript:void(0)" ng-click="confirmSave();">Save</a> |
                            <a href="javascript:void(0)" ng-click="reset(subItems);">Cancel</a>
                        </div>
                    <div class="button-container">
                    <div class="button add disableable" disable="isDisabled" ng-click="open(subItems, 'image')" type="button">Image +</div>
           		</div>
                </div>
                </div>
                    <ul class="image">
                        <li id="image-{{$parent.$index}}-{{$index}}" ng-repeat="image in subItems.images | filter: filterPhoto track by $index">       
                        
                        <div ng-mouseover="hoverIn('remove', 'image-' + $parent.$index + '-' + $index);" ng-mouseleave="hoverOut('remove');" class="button delete item">
                        	<div class="disableable" ng-click="remove(subItems.images, image);" ng-confirm-click="Are you sure you want to delete {{image.title}}?">&#9747;</div>
                        </div>
                        
                        <div class="info-overlay" ng-class="{'remove' : isRemove && selectedID == 'image-' + $parent.$index + '-' + $index, 'edit' : isEdit && selectedID == 'image-' + $parent.$index + '-' + $index, 'new' : isNew && selectedID == 'image-' + $parent.$index + '-' + $index, 'move' : isMove && selectedID == 'image-' + $parent.$index + '-' + $index}"></div>
                        <div class="info" ng-class="{'last':$last}">                                       
                            <div class="image-container">
                              <div class="images-thumb"><img ng-src="/photovault{{image.thumb}}" /></div>
                                <table>
                                    <tr><td><span>Title:</span></td>
                                    <td class="td-large">
                                        <div>
                                            <div ng-hide="isShow('image-' + $parent.$index + '-' + $index)">
                                                {{image.title}}
                                                <a class="edit" href="javascript:void(0)" ng-click="update(image, 'image-' + $parent.$index + '-' + $index);">Edit</a>
                                            </div>
                                            <div ng-show="isShow('image-' + $parent.$index + '-' + $index)">
                                                <input ng-model="image.title"/>
                                                <a href="javascript:void(0)" ng-click="confirmSave();">Save</a> |
                                                <a href="javascript:void(0)" ng-click="reset(image);">Cancel</a>
                                            </div>
                                        </div>
                                    </td></tr>
                                    <tr><td><span>Source: </span></td>
                                        <td class="td-large">
                                            <div>
                                                <a target="_blank" href="/photovault/{{image.url}}">{{image.url}}</a>                                            
                                            </div>
                                        </td></tr>                                  
                                </table>
                                <div class="button-container">

                                  <div ng-mouseover="hoverIn('move', 'image-' + $parent.$index + '-' + $index);" class="button up disableable" disable="isDisabled" ng-mouseleave="hoverOut('move');" ng-click="changePosition(subItems.images,$index, image,false)">&#9650;</div>                                  
                                  <div ng-mouseover="hoverIn('move', 'image-' + $parent.$index + '-' + $index);" class="button down disableable" disable="isDisabled" ng-mouseleave="hoverOut('move');" ng-click="changePosition(subItems.images,$index, image,true)">&#9660;</div>

                                </div>
                                  
                            </div>
                          </div>
                        </li>
                    </ul>
                    <div id="scrollBottom"></div>
            </li>
      
      </ul>
        </div>
      
  </div>
            
<iframe id="my_iframe" name="upload_target" src="" style="width:0;height:0;border:0px solid #fff;"></iframe>

<script src="js/fileUpload.js"></script>

</body>
</html>