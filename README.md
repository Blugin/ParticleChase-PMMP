[![Telegram](https://img.shields.io/badge/Telegram-PresentKim-blue.svg?logo=telegram)](https://t.me/PresentKim)

[![icon/192x192](meta/icon/192x192.png?raw=true)]()

[![License](https://img.shields.io/github/license/PMMPPlugin/ParticleChase.svg?label=License)](LICENSE)
[![Poggit](https://poggit.pmmp.io/ci.shield/PMMPPlugin/ParticleChase/ParticleChase)](https://poggit.pmmp.io/ci/PMMPPlugin/ParticleChase)
[![Release](https://img.shields.io/github/release/PMMPPlugin/ParticleChase.svg?label=Release)](https://github.com/PMMPPlugin/ParticleChase/releases/latest)
[![Download](https://img.shields.io/github/downloads/PMMPPlugin/ParticleChase/total.svg?label=Download)](https://github.com/PMMPPlugin/ParticleChase/releases/latest)


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




## ChangeLog
### v1.0.0 [![Source](https://img.shields.io/badge/source-v1.0.0-blue.png?label=source)](https://github.com/PMMPPlugin/ParticleChase/tree/v1.0.0) [![Release](https://img.shields.io/github/downloads/PMMPPlugin/ParticleChase/v1.0.0/total.png?label=download&colorB=1fadad)](https://github.com/PMMPPlugin/ParticleChase/releases/v1.0.0)
- First release
  
  
---
### v1.1.0 [![Source](https://img.shields.io/badge/source-v1.1.0-blue.png?label=source)](https://github.com/PMMPPlugin/ParticleChase/tree/v1.1.0) [![Release](https://img.shields.io/github/downloads/PMMPPlugin/ParticleChase/v1.1.0/total.png?label=download&colorB=1fadad)](https://github.com/PMMPPlugin/ParticleChase/releases/v1.1.0)
- \[Changed\] Remove return type hint
- \[Fixed\] Not use sqlite
  
  
---
### v1.2.0 [![Source](https://img.shields.io/badge/source-v1.2.0-blue.png?label=source)](https://github.com/PMMPPlugin/ParticleChase/tree/v1.2.0) [![Release](https://img.shields.io/github/downloads/PMMPPlugin/ParticleChase/v1.2.0/total.png?label=download&colorB=1fadad)](https://github.com/PMMPPlugin/ParticleChase/releases/v1.2.0)
- \[Fixed\] main command config not work
- \[Changed\] translation method
- \[Changed\] command structure
- \[Changed\] Change permisson name
  
  
---
### v1.2.1 [![Source](https://img.shields.io/badge/source-v1.2.1-blue.png?label=source)](https://github.com/PMMPPlugin/ParticleChase/tree/v1.2.1) [![Release](https://img.shields.io/github/downloads/PMMPPlugin/ParticleChase/v1.2.1/total.png?label=download&colorB=1fadad)](https://github.com/PMMPPlugin/ParticleChase/releases/v1.2.1)
- \[Changed\] Add return type hint
- \[Fixed\] Violation of PSR-0
- \[Changed\] Rename main class to ParticleChase
- \[Added\] Add PluginCommand getter and setter
- \[Added\] Add getters and setters to SubCommand
- \[Fixed\] Add api 3.0.0-ALPHA11
- \[Added\] Add website and description
- \[Changed\] Show only subcommands that sender have permission to use
  
  
---
### v1.2.2 [![Source](https://img.shields.io/badge/source-v1.2.2-blue.png?label=source)](https://github.com/PMMPPlugin/ParticleChase/tree/v1.2.2) [![Release](https://img.shields.io/github/downloads/PMMPPlugin/ParticleChase/v1.2.2/total.png?label=download&colorB=1fadad)](https://github.com/PMMPPlugin/ParticleChase/releases/v1.2.2)
- \[Fixed\] Split task class for fix Violation of PSR-0