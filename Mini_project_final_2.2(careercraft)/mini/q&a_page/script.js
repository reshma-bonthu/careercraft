// Function to post a new doubt

function postDoubt() {
    const doubtInput = document.getElementById('doubtInput');
    const doubtText = doubtInput.value.trim();

    if (doubtText !== '') {
        const data = new FormData();
        data.append('doubtText', doubtText);

        fetch('post_doubt.php', {
            method: 'POST',
            body: data
        })
        .then(response => response.json())
        .then(result => {
            console.log("bhavya")
            console.log(result)
            if (result.status === "Success") {
                displayDoubts();
            } else {
                alert('Error posting doubtss: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });

        doubtInput.value = '';
    } else {
        alert('Doubt cannot be empty.');
    }
}
// Function to display all doubts
function displayDoubts() {
    fetch('get_doubts.php')
    .then(response => {
        // Check if response is OK and if the response is JSON
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json(); // Parse JSON
    })
    .then(doubts => {
        const doubtsContainer = document.getElementById('doubtsContainer');
        doubtsContainer.innerHTML = ''; // Clear previous doubts

        doubts.forEach(doubt => {
            const doubtDiv = document.createElement('div');
            doubtDiv.className = 'doubt';

            const doubtTitle = document.createElement('h3');
            doubtTitle.innerText = `Asked by: ${doubt.username}`;
            doubtDiv.appendChild(doubtTitle);

            const doubtText = document.createElement('p');
            doubtText.innerText = doubt.doubt_text; // Ensure property name is correct
            doubtDiv.appendChild(doubtText);

            const postReplyBtn = document.createElement('button');
            postReplyBtn.innerText = 'Post Reply';
            postReplyBtn.onclick = function() {
                const replyText = prompt('Enter your reply:');
                if (replyText) {
                    const replyData = new FormData();
                    replyData.append('doubtId', doubt.id);
                    replyData.append('username', currentUsername);
                    replyData.append('replyText', replyText);

                    fetch('post_reply.php', {
                        method: 'POST',
                        body: replyData
                    })
                    .then(response => response.text())
                    .then(result => {
                        if (result === 'Success') {
                            displayDoubts();
                        } else {
                            alert('Failed to post reply');
                        }
                    });
                }
            };
            doubtDiv.appendChild(postReplyBtn);

            const viewRepliesBtn = document.createElement('button');
            viewRepliesBtn.innerText = 'View Replies';
            viewRepliesBtn.onclick = function() {
                const repliesDiv = document.querySelector(`#replies-${doubt.id}`);
                repliesDiv.style.display = repliesDiv.style.display === 'none' ? 'block' : 'none';
                viewRepliesBtn.innerText = repliesDiv.style.display === 'none' ? 'View Replies' : 'Hide Replies';
            };
            doubtDiv.appendChild(viewRepliesBtn);

            const repliesDiv = document.createElement('div');
            repliesDiv.id = `replies-${doubt.id}`;
            repliesDiv.className = 'replies';
            repliesDiv.style.display = 'none';

            fetch(`get_replies.php?doubtId=${doubt.id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json(); // Parse JSON
            })
            .then(replies => {
                replies.forEach(reply => {
                    const replyDiv = document.createElement('div');
                    replyDiv.className = 'reply';
                    replyDiv.innerText = `${reply.username}: ${reply.reply_text}`;
                    repliesDiv.appendChild(replyDiv);
                });
            });

            doubtDiv.appendChild(repliesDiv);
            doubtsContainer.appendChild(doubtDiv);
        });
    })
    .catch(error => {
        console.error('There was a problem with the fetch operation:', error);
    });
}
// Load doubts on page load
window.onload = displayDoubts;