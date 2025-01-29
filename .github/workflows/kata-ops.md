### 🌮 Utilisation de l'Image Docker pour `breizhsport-product`

Cette documentation explique comment utiliser l’image Docker **`pabiosskorp/breizhsport-product`** pour déployer et tester L'api.

---

## 🚀 **1⃣ Récupérer l’image**
Avant d’exécuter L'api, assure-toi d’avoir Docker installé. Ensuite, télécharge l’image avec :
```sh
docker pull pabiosskorp/breizhsport-product:latest
```

---

## 🏃 **2⃣ Lancer le conteneur en local**
Tu peux exécuter L'api sur ton poste en exposant un port :
```sh
docker run -d -p 8080:80 pabiosskorp/breizhsport-product:latest
```
- 🔹 `-d` : Démarre le conteneur en mode détaché (en arrière-plan).
- 🔹 `-p 8080:80` : Expose le port **80** du conteneur sur le port **8080** de l’hôte.

L'api sera alors accessible sur **http://localhost:8080**.

---

## 🛠 **3⃣ Déploiement sur un Serveur**
Sur un serveur distant, exécute :
```sh
docker pull pabiosskorp/breizhsport-product:latest
docker run -d -p 80:80 --restart always pabiosskorp/breizhsport-product:latest
```
- 🔹 `--restart always` : Redémarre automatiquement le conteneur en cas de crash ou reboot du serveur.

Si tu utilises **docker-compose**, voici un fichier `docker-compose.yml` :
```yaml
version: '3'
services:
  breizhsport-product:
    image: pabiosskorp/breizhsport-product:latest
    ports:
      - "80:80"
    restart: always
```
Lancer avec :
```sh
docker-compose up -d
```

---

## 🌍 **4⃣ Déploiement avec Kubernetes**
Si ton infrastructure utilise Kubernetes, utilise ce fichier `deployment.yaml` :
```yaml
apiVersion: apps/v1
kind: Deployment
metadata:
  name: breizhsport-product
spec:
  replicas: 3
  selector:
    matchLabels:
      app: breizhsport-product
  template:
    metadata:
      labels:
        app: breizhsport-product
    spec:
      containers:
      - name: breizhsport-product
        image: pabiosskorp/breizhsport-product:latest
        ports:
        - containerPort: 80
```
Déployer avec :
```sh
kubectl apply -f deployment.yaml
```

---

## 🚫 **5⃣ Arrêter et Supprimer un Conteneur**
Si tu veux **arrêter et supprimer** un conteneur en cours d’exécution :
```sh
docker ps  # Liste les conteneurs en cours
docker stop <CONTAINER_ID>  # Arrête un conteneur
docker rm <CONTAINER_ID>  # Supprime un conteneur
```

---

## ✅ **6⃣ Vérifier les Logs**
Pour voir les logs du conteneur en temps réel :
```sh
docker logs -f <CONTAINER_ID>
```

---

## 🔄 **7⃣ Mettre à Jour l’Image**
Si une nouvelle version est publiée, mets à jour ton instance avec :
```sh
docker pull pabiosskorp/breizhsport-product:latest
docker stop <CONTAINER_ID>
docker rm <CONTAINER_ID>
docker run -d -p 80:80 pabiosskorp/breizhsport-product:latest
```

---

### 🎯 **Conclusion**
Ce guide permet à n'importe quel membre de l'équipe de **récupérer, exécuter et mettre en production L'api** en toute simplicité. 🚀

Si des questions se posent, merci de contacter **l'équipe DevOps**.

