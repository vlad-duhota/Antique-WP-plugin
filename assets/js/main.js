// countdown function
const timerStart = (countDownDate, elem, callback) => {
    if (countDownDate) {
        let x = setInterval(function () {
            const now = new Date().getTime()

            // Find the distance between now and the count down date
            const distance = countDownDate - now

            // Time calculations for days, hours, minutes and seconds
            const days = Math.floor(distance / (1000 * 60 * 60 * 24))
            let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)) + days * 24
            let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)) + 1
            if (hours < 10) {
                hours = '0' + hours
            }
            if (minutes < 10) {
                minutes = '0' + minutes
            }
            elem.innerHTML = `<span>${hours}</span> : <span>${minutes}</span>`

            if (distance < 0) {
                clearInterval(x)
                callback()
            }
        }, 1000)
    }
}

// sale functionality script

const beforeTimer = document.querySelector('#antique-timer-before')
const afterTimer = document.querySelector('#antique-timer-after')
const mainTimer = document.querySelector('#antique-timer-main')
if (mainTimer && beforeTimer && afterTimer) {
    // find <section> with timer
    document.querySelectorAll('section').forEach(section => {
        section.style.display = 'none';
    })
    const beforePage = beforeTimer.closest('section')
    const afterPage = afterTimer.closest('section')
    beforePage.classList.add('antique-before-page')
    afterPage.classList.add('antique-after-page')

    // find all section 
    const shopSections = document.querySelectorAll('section:not(.antique-before-page, .antique-after-page)')

    const countDownDateBefore = new Date(beforeTimer.dataset.timer).getTime()
    const countDownDateSale = new Date(mainTimer.dataset.timer).getTime()
    if (new Date().getTime() < new Date(beforeTimer.dataset.timer).getTime()) {
        shopSections.forEach(shopSection => {
            shopSection.style.display = 'none'
        })
        afterPage.style.display = 'none'
        beforePage.style.display = 'block'
    }
    timerStart(countDownDateBefore, beforeTimer, () => {
        shopSections.forEach(shopSection => {
            shopSection.style.display = 'block'
        })
        beforePage.style.display = 'none'
    })
    timerStart(countDownDateSale, mainTimer, () => {
        if (new Date().getTime() > new Date(beforeTimer.dataset.timer).getTime()) {
            shopSections.forEach(shopSection => {
                shopSection.style.display = 'none'
            })
            afterPage.style.display = 'block'
        } else {
            shopSections.forEach(shopSection => {
                shopSection.style.display = 'none'
            })
            beforePage.style.display = 'block'
            afterPage.style.display = 'none'
        }

    })
}


