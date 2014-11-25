<html>
<head>
  <title>idomoo File System App!</title>
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="public/css/style.css" rel="stylesheet">

  <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.2.19/angular.min.js"></script>
  
<script src="public/js/app.js"></script>

  <link rel="shortcut icon" href="public/img/favicon.ico" type="image/x-icon">
  
</head>
<body ng-app="idomoo">
      
      <div class="main" ng-controller="MainCtrl">
  <div class="page-header">
    <h1>File System</h1>
  </div>

<div >
    <iframe id="uploadTrg" name="uploadTrg" height="0" width="0" frameborder="0" scrolling="no"></iframe>

    <script type="text/ng-template" id="node_template.html">
      <div class="details-view"><i ng-class="{'glyphicon glyphicon-folder-open': node.is_dir, 'glyphicon glyphicon-file': !node.is_dir}"></i> {{node.label}} </div>
        
         <div class="edit-view" ng-show="node.label.length > 0">
      
         <button class="btn btn-primary btn-warning" ng-hide="node.editSave" type="button" ng-click="node.editSave = !node.editSave">Rename</button>          
          <div class="edit-view" ng-show="node.editSave">
            <input type="text" ng-model="node.new_name" placeholder="{{node.label}}"/>
            <button class="btn btn-primary btn-success" type="button" ng-click="renameItem(node); node.editSave = !node.editSave">Save</button>
          </div>

         
        <button class="btn btn-danger" type="button" ng-click="delete(node);" ng-show="node.label.length > 0" >Delete!</button>
        <div style="display:inline-block">
        <form method="post" enctype="multipart/form-data" action="app/ajax_url.php" ng-show="node.is_dir" target="uploadTrg">
          <input type="hidden" name="action" value="uploadFile" />
          <input type="hidden" name="dirname" ng-value="node.path + '/' + node.label" />
          
          <div class="btn btn-primary btn-file"> 
            <i class="glyphicon glyphicon-folder-open"></i> 
              Browse<input name="name" type="file" multiple="true" class="">
          </div>
          <input class="btn btn-primary uploader" type="submit" ng-click="uploadFile(node);" value="Upload File"/>
        </form>

      </div>
        <button class="btn btn-primary btn-warning" ng-hide="node.creatSave || !node.is_dir" type="button" ng-click="node.creatSave = !node.creatSave">Create Folder</button>          
          <div class="edit-view" ng-show="node.creatSave">
            <input type="text" ng-model="node.new_folder" /> 
            <button class="btn btn-primary btn-success" type="button" ng-click="createFolder(node); node.creatSave = !node.creatSave">Save</button>
          </div>      

      </div>
        <ul ng-show="node.label.length > 0"><li data-ng-repeat = "node in node.nodes"
        data-ng-include = "'node_template.html'" ng-init="node.editSave = false" ng-show="node.label.length > 0">

        </li>
    </ul>
    </script>
    <ul >
        <li data-ng-repeat="node in items track by $index"  ng-init="node.editSave = false; node.creatSave = false;" ng-show="node.label.length > 0" data-ng-include="'node_template.html'">       
          
        </li>
    </ul>
</div>

      

    </div>
</body>
</html>



