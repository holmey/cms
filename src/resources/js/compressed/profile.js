!function(a){function b(b){"undefined"!=typeof b.html&&(a(".user-photo").replaceWith(b.html),"undefined"!=typeof changeSidebarPicture&&changeSidebarPicture&&a("#user-photo").find("> img").replaceWith(a("#current-photo").find("> img").clone()),c())}function c(){e.uploadButton=a(".user-photo-controls .upload-photo"),e.deleteButton=a(".user-photo-controls .delete-photo"),d=new Craft.ImageUpload(e)}var d=null,e={postParameters:{userId:a(".user-photo").attr("data-user")},modalClass:"profile-image-modal",uploadAction:"users/upload-user-photo",deleteMessage:Craft.t("app","Are you sure you want to delete this photo?"),deleteAction:"users/delete-user-photo",cropAction:"users/crop-user-photo",areaToolOptions:{aspectRatio:"1",initialRectangle:{mode:"auto"}},onImageSave:function(a){b(a)},onImageDelete:function(a){b(a)}};a("input[name=userId]").val()&&c()}(jQuery);
//# sourceMappingURL=profile.js.map