[![Telegram](https://img.shields.io/badge/Telegram-PresentKim-blue.svg?logo=telegram)](https://t.me/PresentKim)

[![icon/192x192](assets/icon/192x192.png?raw=true)]()

[![License](https://img.shields.io/github/license/PresentKim/ParticleChase-PMMP.svg?label=License)](LICENSE)
[![Release](https://img.shields.io/github/release/PresentKim/ParticleChase-PMMP.svg?label=Release)](https://github.com/PresentKim/ParticleChase-PMMP/releases/latest)
[![Download](https://img.shields.io/github/downloads/PresentKim/ParticleChase-PMMP/total.svg?label=Download)](https://github.com/PresentKim/ParticleChase-PMMP/releases/latest)


A plugin set particle fallow player for PocketMine-MP

## Command
Main command : `/particlechase <set | remove | list | lang | reload | save>`

| subcommand | arguments                                           | description                |
| ---------- | --------------------------------------------------- | -------------------------- |
| Set        | \<player name\> \<particle name\> \[mode\] \[data\] | Set player's particle      |
| Remove     | \<player name\>                                     | Remove player's particle   |
| List       | \[page\]                                            | Show particle setting list |
| Lang       | \<language prefix\>                                 | Load default lang file     |
| Reload     |                                                     | Reload all data            |
| Save       |                                                     | Save all data              |




## Permission
| permission               | default | description       |
| ------------------------ | ------- | ----------------- |
| particlechase.cmd        | OP      | main command      |
|                          |         |                   |
| particlechase.cmd.set    | OP      | set subcommand    |
| particlechase.cmd.remove | OP      | remove subcommand |
| particlechase.cmd.list   | OP      | list subcommand   |
| particlechase.cmd.lang   | OP      | lang subcommand   |
| particlechase.cmd.reload | OP      | reload subcommand |
| particlechase.cmd.save   | OP      | save subcommand   |