_stool()
{
        COMP_WORDBREAKS=${COMP_WORDBREAKS//:}
        COMMANDS=`stool --raw --no-ansi list | sed "s/[[:space:]].*//g"`
        COMPREPLY=(`compgen -W "$COMMANDS" -- "${COMP_WORDS[COMP_CWORD]}"`)
        return 0
}
complete -F _stool stool
