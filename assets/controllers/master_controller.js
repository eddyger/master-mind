import { Controller } from '@hotwired/stimulus';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
    connect() {



        document.addEventListener("turbo:load", (event) => {
            const allSelects = document.getElementsByTagName('select');
            const allAnchorBindToSelect = document.getElementsByClassName('bind-to-select');
            const buttonPlay = document.getElementById('button-play');
            // const buttonMusic = document.getElementById('music-button');
            const musicPlayer = document.getElementById("music-player");
            
            stopPlayer();

            buttonPlay.disabled = true;

            /*
            buttonMusic.addEventListener('click', (event)=>{
                stopPlayer();
            });
            */

            document.getElementById('form-play').addEventListener("submit", (event) => {
                buttonPlay.disabled = true;
            });

            for (let i = 0 ; i < allSelects.length ; i++){
                allSelects[i].addEventListener('change', (event) => {
                    checkIfShowButton();
                });
            }

            for (let i = 0 ; i < allAnchorBindToSelect.length ; i++){
                allAnchorBindToSelect[i].addEventListener('click', (event) => {
                    event.preventDefault();
                    let value = event.currentTarget.getAttribute('data-value');
                    let selectId = event.currentTarget.getAttribute('data-select');
                    let position = event.currentTarget.getAttribute('data-position');
                    let imagePlayed = document.getElementById('img-played-'+ position);
                    imagePlayed.src = event.target.src;
                    let select = document.getElementById(selectId);
                    select.value = value;
                    checkIfShowButton();
                },false);
            }
            

            function checkIfShowButton() {
                for (let i = 0 ; i < allSelects.length ; i++){
                    if (allSelects[i].value === '') {
                        buttonPlay.disabled = true;
                        return;
                    }
                }
                buttonPlay.disabled = false;
            }

            function stopPlayer() {
                musicPlayer.pause();
                musicPlayer.currentTime = 0;
            }

        });
    }
}
