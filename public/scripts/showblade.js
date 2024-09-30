$(document).on("submit", ".like-form", function (e) {
    e.preventDefault();

    var form = $(this);

    $.ajax({
        type: "POST",
        url: form.attr("action"),
        data: form.serialize(),
        success: function (response) {
            if (response.liked) {
                form.find(".like-btn").html(
                    '<i class="fas fa-thumbs-up"></i> Unlike'
                );
            } else {
                form.find(".like-btn").html(
                    '<i class="fas fa-thumbs-up"></i> Like'
                );
            }
            form.siblings(".likes-count").text(response.likes_count + " likes");
        },
        error: function (xhr) {
            console.error("Error:", xhr.responseText);
        },
    });
});

function toggleReplyForm(commentId) {
    let replyForm = document.getElementById(`reply-form-${commentId}`);
    $(replyForm).slideToggle();
}

$("#commentForm").on("submit", function (e) {
    e.preventDefault();
    var form = $(this);

    $.ajax({
        type: "POST",
        url: form.attr("action"),
        data: form.serialize(),
        success: function (response) {
            if (response) {
                $("#comments-container").append(`
                    <div class="comment">
                        <div class="comment-box">
                            <img src="${response.user_image}" alt="User Image" class="profile-picture">
                            <strong class="comment-author">${response.user_name}</strong>
                            <span class="text-muted ml-2">${response.time_ago}</span>
                            <button class="btn btn-link reply-button"
                            onclick="toggleReplyForm({{ $comment->id }})">Reply</button>
                            <div id="comment-{{ $comment->id }}">
                                <button class="btn btn-like btn-primary btn-sm mx-2" data-comment-id="{{ $comment->id }}">
                                    <span class="like-count">{{ $comment->likedByUsers->count() }}</span> Likes
                                </button>
                            </div>

                            @if (auth('admin')->user() || (auth()->id() === $comment->user_id || auth()->id() === $post->user->id))
                                <button class="btn btn-danger btn-sm delete-comment"
                                    data-comment-id="{{ $comment->id }}">Delete</button>
                            @endif
                        </div>
                        <p>${response.content}</p>
                    </div>
                `);
            } else {
                console.error("Error in data");
            }
            form.find('textarea[name="content"]').val("");
        },
        error: function (xhr) {
            console.error("Error:", xhr.responseText);
        },
    });
});

$(document).on("submit", ".ajax-reply-form", function (e) {
    e.preventDefault();

    var form = $(this);
    var commentId = form.find('input[name="parent_id"]').val();

    $.ajax({
        type: "POST",
        url: form.attr("action"),
        data: form.serialize(),
        success: function (response) {
            if (response) {
                $(`#reply-form-${commentId}`).before(`
                <div class="reply">
                    <div class="comment-box">
                        <img src="${response.user_image}" alt="User Image" class="profile-picture">
                        <strong class="comment-author">${response.user_name}</strong>
                        <span class="text-muted ml-2">${response.time_ago}</span>
                    </div>
                    <p>${response.content}</p>
                </div>
            `);
                form.find("textarea").val("");
                form.slideUp();
            }
        },
        error: function (xhr) {
            console.error("Error:", xhr.responseText);
        },
    });
});

$(document).on("click", ".delete-comment", function (e) {
    e.preventDefault();

    var commentId = $(this).data("comment-id");
    var url = `/posts/{{ $post->pid }}/comments/${commentId}`;

    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "DELETE",
                url: url,
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function (response) {
                    Swal.fire("Deleted!", response.message, "success");
                    $(`button[data-comment-id="${commentId}"]`)
                        .closest(".comment")
                        .remove();
                },
                error: function (xhr) {
                    console.error("Error:", xhr.responseText);
                    Swal.fire(
                        "Error!",
                        "There was a problem deleting the comment.",
                        "error"
                    );
                },
            });
        }
    });
});

$(document).on("click", ".delete-reply", function (e) {
    e.preventDefault();

    var commentId = $(this).data("comment-id");
    var replyId = $(this).data("reply-id");
    var url = `/posts/{{ $post->pid }}/comments/${commentId}/reply/${replyId}`;

    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "DELETE",
                url: url,
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function (response) {
                    Swal.fire("Deleted!", response.message, "success");
                    $(`button[data-reply-id="${replyId}"]`)
                        .closest(".reply")
                        .remove();
                },
                error: function (xhr) {
                    console.error("Error:", xhr.responseText);
                    Swal.fire(
                        "Error!",
                        "There was a problem deleting the reply.",
                        "error"
                    );
                },
            });
        }
    });
});

$(document).on("click", ".btn-like", function () {
    var commentId = $(this).data("comment-id");
    var btn = $(this);

    $.ajax({
        type: "POST",
        url: `/comments/${commentId}/like`,
        data: {
            _token: "{{ csrf_token() }}",
        },
        success: function (response) {
            btn.find(".like-count").text(response.likes_count);
        },
        error: function (xhr) {
            console.error("Error:", xhr.responseText);
        },
    });
});
