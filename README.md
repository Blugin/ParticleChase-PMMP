# [![icon/192x192](assets/icon/192x192.png?raw=true)]() DustBin  
__A plugin for [PMMP](https://pmmp.io) :: Set particle fallow player!__  
  
[![license](https://img.shields.io/github/license/organization/ParticleChase-PMMP.svg?label=License)](../master/LICENSE)
[![release](https://img.shields.io/github/release/organization/ParticleChase-PMMP.svg?label=Release)](../../releases/latest)
[![download](https://img.shields.io/github/downloads/organization/ParticleChase-PMMP/total.svg?label=Download)](../../releases/latest)
[![Build status](https://ci.appveyor.com/api/projects/status/o527umpos3igmfll/branch/master?svg=true)](https://ci.appveyor.com/project/PresentKim/humanoid-pmmp/branch/master)

## What is this?   
A plugin set particle fallow player for PocketMine-MP

## Command
Main command : `/particlechase <set | remove | list | lang | reload | save>`

| subcommand | arguments                                           | description                |
| ---------- | --------------------------------------------------- | -------------------------- |
| Set        | \<player name\> \<particle name\> \[mode\] \[data\] | Set player's particle      |
| Remove     | \<player name\>                                     | Remove player's particle   |
| List       | \[page\]                                            | Show particle setting list |




## Permission
| permission               | default | description       |
| ------------------------ | ------- | ----------------- |
| particlechase.cmd        | OP      | main command      |
|                          |         |                   |
| particlechase.cmd.set    | OP      | set subcommand    |
| particlechase.cmd.remove | OP      | remove subcommand |
| particlechase.cmd.list   | OP      | list subcommand   |
