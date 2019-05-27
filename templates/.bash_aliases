alias ..="cd .."
alias ...="cd ../.."

alias h='cd ~'
alias c='clear'
alias art=artisan
alias l='ls -all'

alias phpspec='vendor/bin/phpspec'
alias phpunit='vendor/bin/phpunit'

alias nrd="npm run dev"
alias nrw="npm run watch"
alias nrww="npm run watch-poll"
alias nrh="npm run hot"
alias nrp="npm run production"

alias yrd="yarn run dev"
alias yrw="yarn run watch"
alias yrwp="yarn run watch-poll"
alias yrh="yarn run hot"
alias yrp="yarn run production"

alias ci="composer install $*"
alias freshs="php artisan migrate:fresh --seed"

function artisan() {
    php artisan "$@"
}

function dusk() {
    pids=$(pidof /usr/bin/Xvfb)

    if [ ! -n "$pids" ]; then
        Xvfb :0 -screen 0 1280x960x24 &
    fi

    php artisan dusk "$@"
}
