## Base commands
### Install

```
make install
```

Up containers. Equals -  `docker-compose up -d`
``` 
make up
```


### Uninstall

Destroy related containers, images and volumes:
```
make destroy
```

Down related containers, save volumes and images:
```
make down
```

### Containers

App container console:
```
make app
```

## Additional commands
### CodeSniffer

Check code-style:
```
make cs
```

Check code-style and fix it:
```
make csf
```