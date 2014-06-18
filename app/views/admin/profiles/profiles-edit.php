<h1>Profiles </h1>
<div class="form-horizontal">
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Salutation</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="salutation" ng-model="profile.salutation" placeholder="">
        </div>
    </div>
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">First Name</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="fname" ng-model="profile.firstName" placeholder="">
        </div>
    </div>
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Last Name</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="lname" ng-model="profile.lastName" placeholder="">
        </div>
    </div>
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Email</label>
        <div class="col-sm-10">
            <input type="email" class="form-control" id="email" ng-model="profile.email" placeholder="">
        </div>
    </div>
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Phone</label>
        <div class="col-sm-10">
            <input type="tel" class="form-control" id="phone" ng-model="profile.phone" placeholder="">
        </div>
    </div>
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Company</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="company" ng-model="profile.company" placeholder="">
        </div>
    </div>
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Notes</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="company" ng-model="profile.notes" placeholder="">
        </div>
    </div>
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Wholesale</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="wholesale" ng-model="profile.wholesale" placeholder="">
        </div>
    </div>
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Admin Notes</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="adminNotes" ng-model="profile.adminNotes" placeholder="">
        </div>
    </div>
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Creation Date</label>
        <div class="col-sm-10">
            <input type="date" class="form-control" id="adminNotes" ng-model="profile.createdOn" placeholder="">
        </div>
    </div>
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">Last Update</label>
        <div class="col-sm-10">
            <input type="date" class="form-control" id="adminNotes" ng-model="profile.lastUpdate" placeholder="">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button ng-click="saveProfile()" class="btn btn-primary">Save</button>
            <button ng-click="cancelEdit()" class="btn btn-default">Cancel</button>
        </div>
    </div>
</div>