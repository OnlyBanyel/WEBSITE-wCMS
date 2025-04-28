document.addEventListener("DOMContentLoaded", () => {
  const video = document.getElementById("delayedVideo")
  const container = document.querySelector(".homepage-video-container")

  setTimeout(() => {
    video.play()
    video.classList.add("playing") // Make video visible
    container.classList.add("video-playing") // Remove gradient
  }, 1000)
})
