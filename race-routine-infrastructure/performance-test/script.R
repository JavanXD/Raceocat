library(ggplot2)
library(readr)
library(magrittr)
library(dplyr)
library(lattice)
library(xts)
library(lubridate)

# Setze Optionen für Anzeige der Nachkommastellen und der Milisekunden
options(digits=6)
options(digits.secs=4)

#setwd("~/Projects/raceoact/race-routine-infrastructure/performance-test/")
#basename(getwd())
mydata <- read.csv("3server-2repeats-10connections/log.csv", colClasses=c("ip"="character","microtime"="character"))
df <- data.frame(mydata)

# Konvertiere microtime zu Date
df$time <- as.POSIXct(as.numeric(df$microtime), origin="1970-01-01")

# Auszug aus Datensatz
head(df)

##### Datensätze

# Erzeuge neuen Data Frame gruppiert nach 100ms, 50ms, 25ms etc.
interval_df_25 <- df %>%
  mutate(interval = floor_date(time, ".025s")) %>% 
  group_by(interval) %>% 
  count() %>%  # berechne Anzahl pro Gruppe
  na.omit()

# Erzeuge neuen Data Frame gruppiert nach 100ms, 50ms, 25ms etc.
interval_df_50 <- df %>%
  mutate(interval = floor_date(time, ".05s")) %>% 
  group_by(interval) %>% 
  count(ip) %>%  # berechne Anzahl pro Gruppe pro IP
  na.omit()

# Ausgabe des gruppierten Datensatzes
interval_df_25
interval_df_50

###### Graphen

# Streudiagramm: Herkunft und Zeitpunkt der Einträge
p1 <- ggplot(df, aes(time, ip, colour = ip)) +
  geom_jitter() + # Auch aussagekräftig: geom_bin2d()
  scale_colour_manual(values = c("red", "blue", "darkgreen", "black", "white"), name = "Race Server") + 
  labs(x = toString("Sekunden"), y = toString("Race Server"), title = toString("Herkunft und Zeitpunkt der Einträge"))

p1
dev.print(png, filename="p1.png", units="in", width=7, height=4, res=200)

# Histogram: Häufigste Differenzen zweier Einträge nach einem Angriff
p2 <- histogram(unclass(-diff(df$time)), aspect = 0.5, xlab="Zeitdifferenzen zwischen den Einträgen [s]", ylab="Prozentsatz aller Einträge [%]", xlim=c(0,-1.5), breaks=150) 

p2
dev.print(png, filename="p2.png", units="in", width=7, height=4, res=200)

# Balkendiagramm: Zeitliche Verteilung der Einträge
p3 <- interval_df_25 %>% ggplot(aes(x=interval, y=n)) +
  geom_col(fill="red", alpha=0.5) + 
  labs(x = toString("Zeitpunkt des Eintrags (gruppiert in 25ms Intervalle) [s]"), y = toString("Anzahl an Einträgen"), title = toString("Zeitliche Verteilung der Einträge"))

p3
dev.print(png, filename="p3.png", units="in", width=7, height=4, res=200)

# Balkendiagramm: Zeitliche Verteilung der Einträge unterschieden nach Race Server
p4 <- ggplot(interval_df_50, aes(x=interval, y=n,fill = ip)) +
  geom_col(alpha=0.5) + 
  scale_fill_manual(values = c("red", "blue", "darkgreen", "black", "white"), name = "Race Server") + 
  labs(x = toString("Zeitpunkt des Eintrags (gruppiert in 50ms Intervalle) [s]"), y = toString("Anzahl an Einträgen"), title = toString("Zeitliche Verteilung und Herkunft der Einträge"))

p4
dev.print(png, filename="p4.png", units="in", width=7, height=4, res=200)