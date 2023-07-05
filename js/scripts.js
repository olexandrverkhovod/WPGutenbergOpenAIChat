document.addEventListener('DOMContentLoaded', () => {
    let chatSubmit = document.querySelectorAll(".ai-chat"); 
    
    if (chatSubmit) {
        chatSubmit.forEach(el => el.addEventListener('submit', (e) => {
            e.preventDefault();
            let chatWindow = el.previousSibling;
            const formData = new FormData(el);
            let humanMessage = formData.get("message");
            formData.append('action', 'ai_send');
            chatWindow.innerHTML += '\n<b>Human:</b> '+humanMessage;
            chatWindow.scrollTop = chatWindow.scrollHeight;
            formData.append('chat_body', chatWindow.innerHTML);
            el.getElementsByClassName('input')[0].value = '';
            fetch(ajaxObject.ajaxurl, {
                method: "POST",
                body: formData
            }).then(
                response => response.json()
            ).then(
                (response) => { 
                    let errorAnswear = JSON.parse(response.data).error;
                    if(errorAnswear){
                        chatWindow.innerHTML = errorAnswear.message; 
                    }else{
                        let botAnswear = JSON.parse(response.data).choices[0].text.split(/[\n\r]+/).join('');
                        chatWindow.innerHTML += '\n'+botAnswear;
                        chatWindow.scrollTop = chatWindow.scrollHeight;
                    }
                }
            )
        }))
    }
})