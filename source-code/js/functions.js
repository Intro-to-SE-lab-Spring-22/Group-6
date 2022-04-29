function selectGetVariable(variable) {
    //get url string after "?"
    query = document.location.search.substring(1);
    var parameters = query.split("&");

    //cycle through parameters, check for desired parameter
    for (var i = 0; i < parameters.length; i++) {
        var pair = parameters[i].split("=");
        if (pair[0] == variable) {
            return pair[1];
        }
    }

    return -1; 
}

function scrollToAddComment() {
    document.getElementById("c.new").scrollIntoView();
}

function generatePostElement(postID, postUser, content, num_likes, is_liked, num_comments, is_editable, is_edited, timestamp, has_image, image_filename) {
    // <div class="post" id="p.postID">
    var postDivElement = document.createElement("div");
    postDivElement.classList.add("post");
    postDivElement.id = "p." + postID;

    // <a href="userpage.php?user=postUser">
    var postHeaderLink = document.createElement("a");
    postHeaderLink.href = "userpage.php?user=" + postUser;

    // <div class="post-header-container">
    var postHeaderContainer = document.createElement("div");
    postHeaderContainer.classList.add('post-header-container');

    // <img src="images/profile/default.png />"
    var headerImage = document.createElement("img");
    headerImage.src = "../images/profile/default.png";

    post_setUserProfilePicture(postUser, headerImage);

    // <h2>postUser</h2>
    var headerTextElement = document.createElement("h2");
    var headerTextText = document.createTextNode(postUser);

    headerTextElement.appendChild(headerTextText);
    postHeaderContainer.appendChild(headerImage);
    postHeaderContainer.appendChild(headerTextElement);
    postHeaderLink.appendChild(postHeaderContainer);
    postDivElement.appendChild(postHeaderLink);

    // <p class="post-content">
    var postContentElement = document.createElement("div");
    postContentElement.classList.add("post-content");

    var postContentTextHolder = document.createElement("p");
    var contentText = document.createTextNode(content);

    postContentTextHolder.appendChild(contentText);
    postContentElement.appendChild(postContentTextHolder);

    // console.log(has_image);

    if (has_image) {
        postImagesHolder = document.createElement("div");
        postImagesHolder.classList.add('post-images-holder');

        var imageContainer = document.createElement("div");
        imageContainer.classList.add("post-image-container");
        var image = document.createElement("img");

        image.src = "../images/post/" + postID + "/" + image_filename;

        imageContainer.appendChild(image);
        postImagesHolder.appendChild(imageContainer);
        postContentElement.appendChild(postImagesHolder);
    }

    postDivElement.appendChild(postContentElement);

    // <div class="post-footer">
    var postFooterElement = document.createElement("div");
    postFooterElement.classList.add("post-footer");

    // <div class="post-icon-holder">
    var footerIconHolderElement = document.createElement("div");
    footerIconHolderElement.classList.add("post-icon-holder");

    // <div class="post-icon post-icon-like">
    var likeIconHolderElement = document.createElement("div");
    likeIconHolderElement.classList.add("post-icon", "post-icon-like");

    if (is_liked) {
        likeIconHolderElement.classList.add("is-liked");
    }

    //<div onclick=likePost(this)>
    var likeIconElement = document.createElement("div");
    likeIconElement.onclick = function() {post_likePost(this)};

    // <i class="fa-solid fa-heart"></i>
    var likeIconDisplayElement = document.createElement("i")
    likeIconDisplayElement.classList.add("fa-solid", "fa-heart");

    likeIconElement.appendChild(likeIconDisplayElement);
    likeIconHolderElement.appendChild(likeIconElement);

    // <p>num_likes</p>
    var likeIconTextElement = document.createElement("p");
    var likeIconTextText = document.createTextNode(num_likes);

    likeIconTextElement.appendChild(likeIconTextText);
    likeIconHolderElement.appendChild(likeIconTextElement);

    // <div class="post-icon post-icon-comment">
    var commentIconHolderElement = document.createElement("div");
    commentIconHolderElement.classList.add("post-icon", "post-icon-comment");

    // <div onclick="scrollToAddComment()">
    var commentIconElement = document.createElement("div");
    commentIconElement.onclick = function() {scrollToAddComment()};

    // <i class="fa-solid fa-comment"></i>
    var commentIconDisplayElement = document.createElement("i")
    commentIconDisplayElement.classList.add("fa-solid", "fa-comment");

    commentIconElement.appendChild(commentIconDisplayElement);
    commentIconHolderElement.appendChild(commentIconElement);

    if (num_comments >= 0) {
        // <p>num_comments</p>
        var commentIconTextElement = document.createElement("p");
        var commentIconTextText = document.createTextNode(num_comments);

        commentIconTextElement.appendChild(commentIconTextText);
        commentIconHolderElement.appendChild(commentIconTextElement);

        commentIconElement.onclick = function() {document.location = "post.php?id="+postID}
    }

    if (is_editable) {
        // <div class="post-icon post-icon-edit">
        var editIconHolderElement = document.createElement("div");
        editIconHolderElement.classList.add("post-icon", "post-icon-edit");

        // <a href="post.php?action=edit&id=25">
        var editIconElement = document.createElement("div");
        editIconElement.onclick = function() {makePostEditable(this)};

        // <i class="fa-solid fa-pencil"></i>
        var editIconDisplayElement = document.createElement("i")
        editIconDisplayElement.classList.add("fa-solid", "fa-pencil");

        editIconElement.appendChild(editIconDisplayElement);
        editIconHolderElement.appendChild(editIconElement);
    }

    //<div class="post-date">Edited: Apr 5, 2022 at 18:51:00</div>
    if (is_edited) {
        var date_string = "Edited: "
    }
    else {
        var date_string = "Created: "
    }

    var date = new Date(timestamp);

    var date_options = {year: 'numeric', month: 'short', day: 'numeric'};
    var time_options = {hour: 'numeric', minute: '2-digit', second: '2-digit', hourCycle: 'h24'}

    date_string += date.toLocaleDateString("en-us", date_options) + " at ";
    date_string += date.toLocaleTimeString("en-us", time_options);

    var footerDateHolderElement = document.createElement("div");
    footerDateHolderElement.classList.add("post-date");

    var footerDateText = document.createTextNode(date_string);
    footerDateHolderElement.appendChild(footerDateText);

    footerIconHolderElement.appendChild(likeIconHolderElement);
    footerIconHolderElement.appendChild(commentIconHolderElement);
    if (is_editable) {
        footerIconHolderElement.appendChild(editIconHolderElement);
    }
    postFooterElement.appendChild(footerIconHolderElement);
    postFooterElement.appendChild(footerDateHolderElement);
    postDivElement.appendChild(postFooterElement);

    return postDivElement;
}

function addNewCommentBox(user) {
    //create new textbox header
    var textboxHeader = document.createElement('h2');
    var textboxHeaderText = document.createTextNode(user);
    textboxHeader.appendChild(textboxHeaderText);

    var textboxHeaderLink = document.createElement('a');
    textboxHeaderLink.href = 'userpage.php?user=' + user;
    textboxHeaderLink.appendChild(textboxHeader);

    var headerImage = document.createElement("img");
    headerImage.src = "../images/profile/default.png";

    post_setUserProfilePicture(user, headerImage);

    var commentHeaderContainer = document.createElement("div");
    commentHeaderContainer.classList.add("comment-header-container");

    commentHeaderContainer.appendChild(headerImage);
    commentHeaderContainer.appendChild(textboxHeader);
    textboxHeaderLink.appendChild(commentHeaderContainer);

    //create editable textbox
    var textboxElement = document.createElement('div');
    textboxElement.classList.add('comment');
    textboxElement.id = 'c.new';
    textboxElement.appendChild(textboxHeaderLink);

    //add growable textarea
    var textboxTextarea = document.createElement('textarea');
    textboxTextarea.name = 'comment_content';
    textboxTextarea.oninput = function() {this.parentNode.dataset.replicatedValue = this.value};

    var textboxTextareaContainer = document.createElement('div');
    textboxTextareaContainer.classList.add('grow-wrap', 'comment-content');

    textboxTextareaContainer.appendChild(textboxTextarea);

    textboxElement.appendChild(textboxTextareaContainer);

    //add submit button
    var textboxButton = document.createElement('button');
    textboxButton.type = 'button';
    textboxButton.id = 'create_comment';
    textboxButton.classList.add('btn', 'btn-outline-primary', 'btn-small', 'btn-block');
    textboxButton.onclick = function() {post_addComment()};

    var textboxButtonText = document.createTextNode('Comment');
    textboxButton.appendChild(textboxButtonText);

    var textboxFooter = document.createElement('div');
    textboxFooter.classList.add('comment-footer');
    textboxFooter.appendChild(textboxButton);

    textboxElement.appendChild(textboxFooter);
    document.querySelector('main').appendChild(textboxElement);
}

function updateCommentBox(user, content, commentID, created_at, action) {

    //reference to new textbox
    if (action == 'add') {
        var comment_box = document.getElementById('c.new'); 
    }
    //reference to existing textbox
    else {
        var comment_box = document.getElementById('c.' + commentID);
    }

    //update comment id if necessary
    comment_box.id = 'c.' + commentID;

    //reference to footer
    var comment_footer = comment_box.querySelector('.comment-footer');

    //remove existing content
    comment_box.querySelector('.comment-content').remove();

    //create content holder
    var new_p = document.createElement('p');
    new_p.classList.add('comment-content');
    var new_content = document.createTextNode(content);
    new_p.appendChild(new_content);
    comment_box.insertBefore(new_p, comment_footer);

    //remove button and dates from footer
    while (comment_footer.firstChild) {
        comment_footer.removeChild(comment_footer.firstChild);
    }

    //add icons to footer
    var icon_holder = document.createElement('div');
    icon_holder.classList.add('comment-icon-holder');

    var edit_icon_container = document.createElement('div');
    edit_icon_container.classList.add('comment-icon', 'comment-icon-edit');

    var edit_icon_clickable = document.createElement('div');
    edit_icon_clickable.onclick = function() {makeCommentEditable(this)};

    var edit_icon_display = document.createElement('i');
    edit_icon_display.classList.add('fa-solid', 'fa-pencil');

    edit_icon_clickable.appendChild(edit_icon_display);
    edit_icon_container.appendChild(edit_icon_clickable);
    icon_holder.appendChild(edit_icon_container);
    comment_footer.appendChild(icon_holder);

    //add date to footer
    var date_holder = document.createElement('div');
    date_holder.classList.add('comment-date');

    //comment is not edited
    if (action == 'add') {
        var date_text = document.createTextNode('Created: ' + created_at);
    }

    //comment is edited
    else {
        var date_text = document.createTextNode('Edited: ' + created_at);
    }

    date_holder.appendChild(date_text);
    comment_footer.appendChild(date_holder);
}

function makeCommentEditable(eventElement) {
    //save comment id
    var commentID = eventElement.parentElement.parentElement.parentElement.parentElement.id;

    //reference to comment element
    var comment = document.getElementById(commentID);
    var content = comment.querySelector('.comment-content').childNodes[0].nodeValue.trim();

    //remove static content
    comment.querySelector('.comment-content').remove();

    //add editable textarea
    var new_textbox_textarea = document.createElement('textarea');
    new_textbox_textarea.name = 'comment_content';
    new_textbox_textarea.oninput = function() {this.parentNode.dataset.replicatedValue = this.value};
    var new_content_node = document.createTextNode(content);
    new_textbox_textarea.appendChild(new_content_node);

    //add container for textarea that grows with line breaks
    var new_textbox_textarea_container = document.createElement('div');
    new_textbox_textarea_container.classList.add('grow-wrap', 'comment-content');

    new_textbox_textarea_container.appendChild(new_textbox_textarea);

    //add new footer
    var comment_footer = comment.querySelector('.comment-footer');
    comment.insertBefore(new_textbox_textarea_container, comment_footer);

    comment_footer.querySelector('.comment-icon-holder').remove();

    //add button to save changes
    var new_textbox_button = document.createElement('button');
    new_textbox_button.type = 'button';
    new_textbox_button.id = 'edit_comment';
    new_textbox_button.classList.add('btn', 'btn-outline-primary', 'btn-small', 'btn-block');
    new_textbox_button.onclick = function() {post_editComment(this)};

    var new_textbox_button_text = document.createTextNode('Save');
    new_textbox_button.appendChild(new_textbox_button_text);

    //add date
    var comment_date = comment_footer.querySelector('comment-date');
    comment_footer.prepend(new_textbox_button);
}

function addNewPostBox(postUser) {

    var postDivElement = document.createElement("div");
    postDivElement.classList.add("post");

    var postHeaderElement = document.createElement("a");
    postHeaderElement.setAttribute("href", "userpage.php?user=" + postUser);

    var headerTextElement = document.createElement("h2");
    var headerTextText = document.createTextNode(postUser);

    var headerImage = document.createElement("img");
    headerImage.src = "../images/profile/default.png";

    post_setUserProfilePicture(postUser, headerImage);

    var postHeader = document.createElement("div");
    postHeader.classList.add("post-header-container");

    postHeader.appendChild(headerImage);
    headerTextElement.appendChild(headerTextText);
    postHeader.appendChild(headerTextElement);

    postHeaderElement.appendChild(postHeader);
    postDivElement.appendChild(postHeaderElement)

    var postContentElement = document.createElement("div");
    postContentElement.classList.add("grow-wrap", "post-content");

    var contentTextarea = document.createElement("textarea");
    contentTextarea.name = "post_content";
    contentTextarea.oninput = function() {this.parentNode.dataset.replicatedValue = this.value};
    
    postContentElement.appendChild(contentTextarea);
    postDivElement.appendChild(postContentElement);

    var postFooterElement = document.createElement("div");
    postFooterElement.classList.add("post-footer");

    var footerButton = document.createElement("button");
    footerButton.classList.add("btn", "btn-outline-primary", "btn-small", "btn-block");
    footerButton.onclick = function() {post_createPost()};
    footerButton.id = "submit_post";
    footerButton.type = "submit";
    footerButton.innerHTML = "Save";

    // echo "<input type=\"file\" id=\"upload-image\" name=\"upload-image\" style=\"display:none\" onInput=\"updateImage(this)\">";
    // echo "<div class=\"file-upload-button\" onclick=\"document.getElementById('upload-image').click()\">";
    // echo "<i class = \"fa-solid fa-pencil\"></i>";
    // echo "</div>";

    var uploadImageInput = document.createElement("input");
    uploadImageInput.type = "file";
    uploadImageInput.id = "upload-image";
    uploadImageInput.name = "upload-image";
    uploadImageInput.style = "display:none";
    uploadImageInput.oninput = function() {updatePostImage(this)};

    var uploadImageButton = document.createElement("button");
    uploadImageButton.classList.add("btn", "btn-outline-primary", "btn-small", "btn-block");
    uploadImageButton.onclick = function() {document.getElementById('upload-image').click()};
    uploadImageButton.id = "upload-image-button";
    uploadImageButton.type = "submit";
    uploadImageButton.innerHTML = "Upload Image";

    var footerButtonHolder = document.createElement("div");
    footerButtonHolder.classList.add("footer-button-holder");
    footerButtonHolder.appendChild(footerButton);
    footerButtonHolder.appendChild(uploadImageInput);
    footerButtonHolder.appendChild(uploadImageButton);

    postFooterElement.appendChild(footerButtonHolder);
    postDivElement.appendChild(postFooterElement);

    document.querySelector('main').appendChild(postDivElement);
}

function makePostEditable(eventElement) {
    //save comment id
    var postElement = eventElement.closest('.post');

    var content = postElement.querySelector('.post-content').querySelector("p").childNodes[0].nodeValue.trim();

    if (postElement.querySelector('.post-content').querySelector('.post-images-holder')) {
        var has_image = true;
        var image_src = postElement.querySelector('.post-content').querySelector('.post-images-holder').querySelector('img').src;
    }
    else {
        var has_image = false;
        var image_src = "";
    }

    console.log(image_src);

    //remove static content
    postContentElement = postElement.querySelector('.post-content')
    postContentElement.removeChild(postContentElement.firstChild);
    postContentElement.classList.add('grow-wrap');

    //add editable textarea
    var postContentTextarea = document.createElement('textarea');
    postContentTextarea.name = 'post_content';
    postContentTextarea.oninput = function() {this.parentNode.dataset.replicatedValue = this.value};
    var postContentTextareaText = document.createTextNode(content);
    postContentTextarea.appendChild(postContentTextareaText);

    postContentElement.prepend(postContentTextarea);

    //add new footer
    var postFooter = postElement.querySelector('.post-footer');

    postFooter.querySelector('.post-icon-holder').remove();

    //add button to save changes
    var postFooterButton = document.createElement('button');
    postFooterButton.type = 'button';
    postFooterButton.id = 'edit_post';
    postFooterButton.classList.add('btn', 'btn-outline-primary', 'btn-small', 'btn-block');
    postFooterButton.onclick = function() {post_editPost(this)};

    var postFooterButtonText = document.createTextNode('Save');
    postFooterButton.appendChild(postFooterButtonText);

    var uploadImageInput = document.createElement("input");
    uploadImageInput.type = "file";
    uploadImageInput.id = "upload-image";
    uploadImageInput.name = "upload-image";
    uploadImageInput.style = "display:none";
    uploadImageInput.oninput = function() {updatePostImage(this)};

    var uploadImageButton = document.createElement("button");
    uploadImageButton.classList.add("btn", "btn-outline-primary", "btn-small", "btn-block");
    uploadImageButton.onclick = function() {document.getElementById('upload-image').click()};
    uploadImageButton.id = "upload-image-button";
    uploadImageButton.type = "submit";
    uploadImageButton.innerHTML = "Upload Image";

    var footerButtonHolder = document.createElement("div");
    footerButtonHolder.classList.add("footer-button-holder");
    footerButtonHolder.appendChild(postFooterButton);
    footerButtonHolder.appendChild(uploadImageInput);
    footerButtonHolder.appendChild(uploadImageButton);

    postFooter.prepend(footerButtonHolder);
}

function generateCommentElement(commentID, commentUser, content, is_editable, is_edited, timestamp) {
    var commentElement = document.createElement("div");
    commentElement.id = "c." + commentID;
    commentElement.classList.add("comment");

    // // <a href="userpage.php?user=postUser">
    // var postHeaderLink = document.createElement("a");
    // postHeaderLink.href = "userpage.php?user=" + postUser;

    // // <div class="post-header-container">
    // var postHeaderContainer = document.createElement("div");
    // postHeaderContainer.classList.add('post-header-container');

    // // <img src="images/profile/default.png />"
    // var headerImage = document.createElement("img");
    // headerImage.src = "../images/profile/default.png";

    // // <h2>postUser</h2>
    // var headerTextElement = document.createElement("h2");
    // var headerTextText = document.createTextNode(postUser);

    // headerTextElement.appendChild(headerTextText);
    // postHeaderContainer.appendChild(headerImage);
    // postHeaderContainer.appendChild(headerTextElement);
    // postHeaderLink.appendChild(postHeaderContainer);
    // postDivElement.appendChild(postHeaderLink);

    //create new textbox header
    var commentHeaderText = document.createElement('h2');
    var commentHeaderTextText = document.createTextNode(commentUser);
    commentHeaderText.appendChild(commentHeaderTextText);

    var headerImage = document.createElement("img");
    headerImage.src = "../images/profile/default.png";

    post_setUserProfilePicture(commentUser, headerImage);

    var commentHeader = document.createElement("div");
    commentHeader.classList.add("comment-header-container");

    commentHeader.appendChild(headerImage);
    commentHeader.appendChild(commentHeaderText);

    var commentHeaderLink = document.createElement('a');
    commentHeaderLink.href = 'userpage.php?user=' + commentUser;
    commentHeaderLink.appendChild(commentHeader);
    commentElement.append(commentHeaderLink);

    var commentContentElement = document.createElement('p');
    commentContentElement.classList.add('comment-content');
    var commentContentText = document.createTextNode(content);
    commentContentElement.appendChild(commentContentText);
    commentElement.append(commentContentElement);

    var commentFooter = document.createElement('div');
    commentFooter.classList.add('comment-footer');

    if (is_editable) {

        var commentIconHolderElement = document.createElement('div');
        commentIconHolderElement.classList.add('comment-icon-holder');

        var editIconContainerElement = document.createElement('div');
        editIconContainerElement.classList.add('comment-icon', 'comment-icon-edit');

        var editIconElement = document.createElement('div');
        editIconElement.onclick = function() {makeCommentEditable(this)};

        var editIconDisplayElement = document.createElement('i');
        editIconDisplayElement.classList.add('fa-solid', 'fa-pencil');

        editIconElement.appendChild(editIconDisplayElement);
        editIconContainerElement.appendChild(editIconElement);
        commentIconHolderElement.appendChild(editIconContainerElement);
        commentFooter.appendChild(commentIconHolderElement);
    }

    if (is_edited) {
        var date_string = "Edited: "
    }
    else {
        var date_string = "Created: "
    }

    var date = new Date(timestamp);

    var date_options = {year: 'numeric', month: 'short', day: 'numeric'};
    var time_options = {hour: 'numeric', minute: '2-digit', second: '2-digit', hourCycle: 'h24'}

    date_string += date.toLocaleDateString("en-us", date_options) + " at ";
    date_string += date.toLocaleTimeString("en-us", time_options);

    //add date to footer
    var commentDateHolder = document.createElement('div');
    commentDateHolder.classList.add('comment-date');

    var commentDateText = document.createTextNode(date_string);

    commentDateHolder.appendChild(commentDateText);
    commentFooter.appendChild(commentDateHolder);
    commentElement.appendChild(commentFooter);
    document.querySelector('main').appendChild(commentElement);
}

function updateImage(eventElement) {
    var imageContainer = eventElement.closest(".userpage-display-image");
    var image = imageContainer.querySelector("img");
    files = eventElement.files;
    image.src = URL.createObjectURL(files[0]);

    var formData = new FormData();
    formData.append('image', files[0]);

    post_uploadImage(formData);

    console.log(URL.createObjectURL(files[0]));
}

function updatePosts(post_list) {
    for (var i = 0; i < post_list.length; i++) {
        post_updatePost(post_list[i]);
    }
}

function updatePostImage(eventElement) {
    var postElement = eventElement.closest(".post");
    var postID = postElement.id.substring(2);
    var postContent = postElement.querySelector(".post-content");

    if (!postContent.querySelector('.post-images-holder')) {
        postImagesHolder = document.createElement("div");
        postImagesHolder.classList.add('post-images-holder');
        postContent.appendChild(postImagesHolder);
    }
    else {
        postImagesHolder = postContent.querySelector('.post-images-holder');
    }

    while (postImagesHolder.firstChild) {
        postImagesHolder.removeChild(postImagesHolder.firstChild);
    }

    files = eventElement.files;

    for (var i = 0; i < files.length; i++) {
        var imageContainer = document.createElement("div");
        imageContainer.classList.add("post-image-container");
        var image = document.createElement("img");

        image.src = URL.createObjectURL(files[i]);

        imageContainer.appendChild(image);
        postImagesHolder.appendChild(imageContainer);

        break;
    }

    // var formData = new FormData();
    // formData.append('image', files[0]);

    // // post_uploadImage(formData);

    // console.log("=====");
    // for (var i = 0; i < files.length; i++) {
    //     console.log(URL.createObjectURL(files[i]));
    // }
}
